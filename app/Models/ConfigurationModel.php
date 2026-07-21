<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfigurationModel extends Model
{
    protected $table = 'configurations';
    protected $primaryKey = 'cle';
    protected $returnType = 'array';
    protected $allowedFields = ['cle', 'valeur'];

    public function commissionInteroperateur(): float
    {
        $configuration = $this->find('commission_transfert_interoperateur');
        return max(0, (float) ($configuration['valeur'] ?? 0));
    }

    public function definirCommissionInteroperateur(float $pourcentage): bool
    {
        return $this->save([
            'cle' => 'commission_transfert_interoperateur',
            'valeur' => (string) $pourcentage,
        ]);
    }
}
