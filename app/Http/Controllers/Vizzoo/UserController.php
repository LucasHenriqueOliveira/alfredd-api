<?php

namespace App\Http\Controllers\Vizzoo;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Contract;
use App\Models\User;
use App\Models\SupportClasses\UserPermission;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Vizzoo\VizzooServiceController;

class VizzooUserController extends UserController
{

   public function store(Request $request, $rawData = true)
   {
        $user = User::where('ds_usuario', $request->post('username'))->first();
        if (!$user) {
            $user = parent::store($request, $rawData);
        }
        if (isset($user->cd_usuario)) {
            (new VizzooServiceController)->storeServicesHubs($request->post('company_doc'));
            $services = (new Service)->listServicesByCnpj([$request->post('company_doc')]);
            if (!empty($services)) {
                foreach ($services as $service) {
                    (new UserPermission())::store([
                        'cd_usuario'            => $user->cd_usuario,
                        'cd_emp'                => $user->cd_emp,
                        'cd_arq_tp'             => $service->cd_arq_tp,
                        'fg_recebe_aviso'       => 'S',
                        'fg_recebe_alerta'      => 'S',
                        'fg_altera_status'      => 'S',
                        'fg_exibir'             => 'S',
                        'fg_entrada_manual'     => 'S',
                        'fg_depara_manut'       => 'N',
                        'fg_previsibilidade'    => 'S'
                    ]);
                }
            }
        }
        return $this->response->item($user, new UserTransformer());
        
   }

}