<?php

namespace App\Models;

use CodeIgniter\Model;

class EstructuraModel extends Model
{
    protected $table = 'cat_estructura';
    protected $primaryKey = 'id_estructura';
    protected $allowedFields = ['nombre', 'direccion', 'activo'];
    protected $useTimestamps = false;
}