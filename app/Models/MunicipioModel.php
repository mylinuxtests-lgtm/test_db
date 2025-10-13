<?php

namespace App\Models;

use CodeIgniter\Model;

class MunicipioModel extends Model
{
    protected $table = 'municipios';
    protected $primaryKey = 'id_municipio';
    protected $allowedFields = ['nombre', 'activo'];
    protected $useTimestamps = false;
}