<?php

namespace App\Validations;

class Validation
{
    /**
     * Validate function category
     *
     * @param $request
     */
    public static function validationUsers($request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required|numeric',
        ], [
            'name.require' => 'How often do you enter the name of the user?',
            'email.require' => 'Do you often enter users emails?',
            'phone.require' => 'How often do you chat with peoples phones?',
            'phone.numeric' => 'Is the phone so bad?',
        ]);
    }
}
