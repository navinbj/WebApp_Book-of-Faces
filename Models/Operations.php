<?php
class Operations
{
    /**
     * @param string $data The data to be validated.
     * @return string The validated data.
     */
    function validate($data){
        // Removes any spaces from the data entered.
        $data = trim($data);
        // Escapes quotes from the data entered.
        $data = stripcslashes($data);
        // Escapes any html special characters entered.
        $data = htmlentities($data, ENT_QUOTES);
        // Returns the validated data.
        return $data;
    }

}
