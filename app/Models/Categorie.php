<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'nom',
    ];

    // RELATIONS

    /**
     * Une catégorie peut avoir plusieurs dépenses
     * Exemple: La catégorie "Achat" peut être utilisée pour plusieurs dépenses
     */
    public function depenses()
    {
        return $this->hasMany(Depense::class, 'categorie_id');
    }

    // MÉTHODES HELPER

    /**
     * Vérifie si la catégorie peut être supprimée
     * Une catégorie ne peut être supprimée que si elle n'a aucune dépense associée
     */
    public function peutEtreSupprimee()
    {
        return $this->depenses()->count() === 0;
    }
}
