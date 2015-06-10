<?php
return array(
    'extra_fields' => array(
        'post' => array(
            'news' => array(
                array(
                    'type' => 'select',
                    'attributes' => array(
                        'class' => 'form-control',
                        'name' => 'test',
                    ),
                    'options' => array(
                        'label' => 'test',
                        'value' => array(
                            'a' => 'aaa',
                            'b' => 'bbb'
                        ),
                    )
                ),
                array(
                    'type' => 'text',
                    'attributes' => array(
                        'class' => 'form-control',
                        'name' => 'test-2',
                    ),
                    'options' => array(
                        'label' => 'test 2',
                        'value' => 'bbbb',
                    )
                ),
                array(
                    'type' => 'textarea',
                    'attributes' => array(
                        'class' => 'form-control summernote-lg',
                        'name' => 'test-3',
                    ),
                    'options' => array(
                        'label' => 'test 2',
                        'value' => 'bbbb',
                    )
                )

            )
        )
    )

);