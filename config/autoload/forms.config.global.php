<?php
return array(
    'extra_fields' => array(
        'post' => array(
            'testimonials' => array(
//                array(
//                    'type' => 'select',
//                    'attributes' => array(
//                        'class' => 'form-control',
//                        'name' => 'test',
//                    ),
//                    'options' => array(
//                        'label' => 'test',
//                        'value' => array(
//                            'a' => 'aaa',
//                            'b' => 'bbb'
//                        ),
//                    )
//                ),
                array(
                    'type' => 'text',
                    'attributes' => array(
                        'class' => 'form-control',
                        'name' => 'name_surname',
                    ),
                    'options' => array(
                        'label' => 'Imię i Nazwisko',
                        'value' => '',
                    )
                ),
                array(
                    'type' => 'text',
                    'attributes' => array(
                        'class' => 'form-control',
                        'name' => 'job',
                    ),
                    'options' => array(
                        'label' => 'Zawód',
                        'value' => '',
                    )
                ),
//                array(
//                    'type' => 'textarea',
//                    'attributes' => array(
//                        'class' => 'form-control summernote-lg',
//                        'name' => 'test-3',
//                    ),
//                    'options' => array(
//                        'label' => 'test 2',
//                        'value' => 'bbbb',
//                    )
//                )

            )
        ),
        'users' => array(
            array(
                'type' => 'text',
                'attributes' => array(
                    'class' => 'form-control',
                    'name' => 'education',
                ),
                'options' => array(
                    'label' => 'Wykształcenie',
                    'value' => '',
                )
            ),
            array(
                'type' => 'text',
                'attributes' => array(
                    'class' => 'form-control',
                    'name' => 'experience',
                ),
                'options' => array(
                    'label' => 'Doświadczenie',
                    'value' => '',
                )
            ),
            array(
                'type' => 'textarea',
                'attributes' => array(
                    'class' => 'form-control summernote-lg',
                    'name' => 'about',
                ),
                'options' => array(
                    'label' => 'O sobie',
                    'value' => '',
                )
            ),
        )
    )

);