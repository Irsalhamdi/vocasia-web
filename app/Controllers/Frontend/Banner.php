<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Banner extends FrontendController
{
    protected $format = 'json';
    public function index()
    {
        $banner = $this->model_banner->get_banner();
        foreach ($banner as $b) {
            $data[] = [
            "id" => $b['id_b'],
            "img" => $this->model_banner->get_img($b['id_b']),
            "img_web_mobile_version" => $b['img_web_mobile_version'],
            "url" => $b['url'],
            "status" => $b['status'],
            "date" => $b['date']
        ];
        }
        return $this->respond(get_response($data));
    }

    public function show($id = null)
    {
        $banner = $this->model_banner->get_banner($id);
        if ($banner) {
            return $this->respond(get_response($banner));
        }else {
            return $this->failNotFound();
        }
    }

    public function create()
    {
        $data_banner = $this->request->getJSON();

        $this->model_banner->protect(false)->insert($data_banner);
        return $this->respondCreated(response_create());
    }

    public function update($id = null)
    {
        $data_banner = $this->model_banner->find($id);
        
        $data_banner = $this->request->getJSON();

        $this->model_banner->protect(false)->update($id, $data_banner);
        return $this->respondUpdated(response_update());
    }

    public function  delete($id = null)
    {
        $data_banner = $this->model_banner->find($id);

        if ($data_banner) {
            $this->model_banner->delete($id);
            unlink('uploads/banner_img/' . $data_banner['img']);
            return $this->respondDeleted(response_delete());
        }else {
            return $this->failNotFound();
        }
    }

    public function img($id = null)
    {
        $data_banner = $this->model_banner->find($id);
        $rules = [
            'img' => 'max_size[img,2048]|is_image[img]'
        ];
        if (!$this->validate($rules)) {
            return $this->fail('Failed To Upload Image Please Try Again');
        }else {
            if ($data_banner) {
                $img = $this->request->getFile('img');
                $name = "banner_default_$id.jpg";
        
                $data = [
                'id' => $id,
                'img'  => $name
                ];
                if ($data_banner['img']) {
                    unlink('uploads/banner_img/' . $data_banner['img']);    
                }
                $img->move('uploads/banner_img/', $name);
                $this->model_banner->update($id, $data);
                
                return $this->respondCreated(response_create());

            }else {
                return $this->failNotFound();
            }
        }
    }
}