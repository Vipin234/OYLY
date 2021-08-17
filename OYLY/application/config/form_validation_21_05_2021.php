<?php
$config = array(

       'addRestaurent'=>array(

                array(
                        'field'                 => 'name',
                        'label'                 => 'Restaurant is required',
                        'rules'                 => 'required'
                ),
    
                 array(
                        'field'                 => 'lat',
                        'label'                 => 'lat is required',
                        'rules'                 => 'required'
                ),
                  array(
                        'field'                 => 'lng',
                        'label'                 => 'lng is required',
                        'rules'                 => 'required'
                ),
                   array(
                        'field'                 => 'location',
                        'label'                 => 'location is required',
                        'rules'                 => 'required'
                ),
                 array(
                        'field'                 => 'cuisines',
                        'label'                 => 'cuisines is required',
                        'rules'                 => 'required'
                ),
                 array(
                        'field'                 => 'phone',
                        'label'                 => 'phone is required',
                        'rules'                 => 'required'
                ),
                 array(
                        'field'                 => 'address',
                        'label'                 => 'address is required',
                        'rules'                 => 'required'
                ),
                 array(
                        'field'                 => 'city',
                        'label'                 => 'city is required',
                        'rules'                 => 'required'
                ),
                 array(
                        'field'                 => 'admin_id',
                        'label'                 => 'admin id is required',
                        'rules'                 => 'required'
                )
        )
); 
?>