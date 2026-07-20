<?php

// log info
function log_info($task)
{
    $ci = &get_instance();
    $ci->load->model('Audit_model');


    // Ajusta o timezone para America/Sao_Paulo
    $dt = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    $data = [
        'usuario' => $ci->session->userdata('nome'),
        'ip' => $ci->input->ip_address(),
        'tarefa' => $task,
        'data' => $dt->format('Y-m-d'),
        'hora' => $dt->format('H:i:s'),
    ];

    $ci->Audit_model->add($data);
}
