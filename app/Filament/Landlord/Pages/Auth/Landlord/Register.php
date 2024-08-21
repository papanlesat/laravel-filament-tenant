<?php

namespace App\Filament\Landlord\Pages\Auth\Landlord;

use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\{Select, TextInput};
use Filament\Forms\Components\Component;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use App\Models\Tenant;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getTenantFormUsernameComponent(),
                        $this->getEmailFormComponent(),
                        $this->getTenantFormDomainComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        //$this->getRoleFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
    protected function getTenantFormUsernameComponent(): Component
    {
        return TextInput::make('username')
            ->maxLength(100)
            ->required()
            ->label('username');
    }

    protected function getTenantFormDomainComponent(): Component
    {
        return TextInput::make('website')
            ->prefix('https://')
            ->suffix('.' . collect(config('tenancy.central_domains'))->first())
            ->maxLength(250)
            ->required()
            ->label('Website');
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'buyer' => 'Buyer',
                'seller' => 'Seller',
            ])
            ->default('buyer')
            ->required();
    }

    protected function registerTenant(array $data): array
    {
        $tenant = Tenant::create(['id' => 'foo']);
        $tenant->domains()->create(['domain' => 'foo.localhost']);
        return $tenant;
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (){
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $tenant = Tenant::create([
                'username' => $data['username']
            ]);

            $tenant->domains()->create([
                'domain' => $data['website'] . '.' .collect(config('tenancy.central_domains'))->first()
            ]);

            $data['tenant_id'] = $tenant->id;

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
