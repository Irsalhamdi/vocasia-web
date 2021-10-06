<?php

namespace App\Controllers\Frontend;

use App\Controllers\Frontend\FrontendController;

class Question extends FrontendController
{
    public function index($course_id = null)
    {
        $question_data = $this->model_question->get_list_question_by_course($course_id);
        return $this->respond(get_response($question_data));
    }
}
