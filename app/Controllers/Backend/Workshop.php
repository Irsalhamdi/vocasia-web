<?php

namespace App\Controllers\Backend;

use App\Controllers\Backend\BackendController;

class Workshop extends BackendController
{
    public function index(){
        
        $workshops = $this->model_workshop->get_workshop();

        foreach($workshops as $workshop) {
            $data[] = [
                "id" => $workshop->id,
                "title" => $workshop->title,
                "price" => $workshop->price,
                "url" => base_url() . '/home/workshop_form/' . base64_encode($workshop->title),
                "is_active" => $workshop->is_active,
                "date_added" => $workshop->date_added,
            ];
        }

        return $this->respond(get_response($data));

    }

    public function create(){

        $rules = $this->model_workshop->validationRules;
        if (!$this->validate($rules)) {

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);

        }else{

            $data = $this->request->getJSON();
            $check = $this->model_workshop->check_duplication_workshop($data);

            if($check) {

                $data->is_active = 1;
                $this->model_workshop->save($data);
                return $this->respondCreated(response_create());
            }else{

                return $this->respond([
                    'status' => 403,
                    'error' => true,
                    'data' => [
                        'message' => 'Workshop has been available'
                    ]
                ], 403);
            }
        }     
    }

    public function update($id = null){

        $id = $this->model_workshop->find($id);

        $rules = $this->model_workshop->validationRules;
        if (!$this->validate($rules)) {

            return $this->respond([
                'status' => 403,
                'error' => true,
                'data' => $this->validator->getErrors()
            ], 403);

        }else{

            if(!empty($id)){
            
                $data =  $this->request->getJSON();
                $check = $this->model_workshop->check_duplication_workshop($data); 
                
                if($check){

                    $this->model_workshop->update($id, $data);
                    return $this->respondUpdated(response_update());

                }else{

                    return $this->respond([
                        'status' => 403,
                        'error' => true,
                        'data' => [
                        'message' => 'Workshop has been available'
                        ]
                    ], 403);

                }
            }else{
                return $this->failNotFound();
            }
        }
    }

    public function delete($id = null){

        $id = $this->model_workshop->find($id);

        if(!empty($id)){

            $data = ['is_active' => '0'];

            $this->model_workshop->update($id, $data);
            return $this->respondDeleted(response_delete());

        }else{
            return $this->failNotFound();
        }
    }
}
