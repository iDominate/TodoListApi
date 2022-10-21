<?php
namespace App\Traits;
/**
 * ResponseTrait
 */
trait ResponseTrait
{
    function returnMessage($errors = "no",$message = "Success", $data = null)
    {
        return
         [
            "message" => $message,
            "data" => $data
         ];
    }
}
