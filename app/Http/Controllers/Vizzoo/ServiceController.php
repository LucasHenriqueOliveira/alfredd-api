<?php

namespace App\Http\Controllers\Vizzoo;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Contract;
use App\Models\Company;
use App\Http\Controllers\Controller;

class VizzooServiceController extends Controller
{

     protected $cnpjsAdquirentes = [
        '99999999999999'
     ];
     protected $documents = [
         'COMPFIANTE' => 'ENV',
         'COMPFIPGTO' => 'REC'
     ];

     public function storeServicesHubs($cnpj = '')
     {
        if (!$cnpj) {
            return $this->response->error('cnpj not found.', 404);
        }

        $company = Company::findByNumberDoc($cnpj);
        if (!$company) {
            return response()->json(['error' => 'company not found'], 404);
        }

        //verify if exists contract
        $contract = (new Contract)->exists($company->cd_emp);

        //create contract
        if (!$contract) {
            $contract = (new Contract)->store([
                'cd_emp'    => $company->cd_emp,
                'obs'       => 'Antecipação '.$company->ds_razaosocial
            ]);
        }

        $contractId = $contract->cd_contrato;
        $services = (new Service)->listServicesByCnpj($this->cnpjsAdquirentes, array_keys($this->documents));
        $servicesResponse = [];
        if (!empty($services)) {

            foreach($services as $service) {
                if (isset($this->documents[$service->sg_msg])) {
                    $servicesResponse[] = Service::store([
                        'cd_emp'        => $company->cd_emp,
                        'cd_arq_tp'     => $service->cd_arq_tp,
                        'sg_direcao'    => $this->documents[$service->sg_msg],
                        'cd_contrato'   => $contractId
                    ]);
                }
            }
        }

        return $servicesResponse;
         
     }

}