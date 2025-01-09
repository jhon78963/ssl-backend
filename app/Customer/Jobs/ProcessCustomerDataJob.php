<?php

namespace App\Customer\Jobs;

use App\Customer\Models\Customer;
use App\Customer\Services\CustomerService;
use App\Shared\Services\SharedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCustomerDataJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $personData;

    /**
     * Create a new job instance.
     */
    public function __construct($personData)
    {
        $this->personData = $personData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $formattedData = $this->formatCustomerData($this->personData);
        $customer = new Customer();
        $customer->dni = $formattedData['dni'];
        $customer->name = $formattedData['name'];
        $customer->surname = $formattedData['surname'];
        $customer->save();
        // app(CustomerService::class)->create($formattedData);
    }

    private function formatCustomerData($person): array
    {
        return app(SharedService::class)->convertCamelToSnake([
            'dni' => $person->numeroDocumento,
            'name' => $person->nombres,
            'surname' => trim("{$person->apellidoPaterno} {$person->apellidoMaterno}")
        ]);
    }
}
