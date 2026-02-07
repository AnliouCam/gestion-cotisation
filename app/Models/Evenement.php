<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evenement extends Model
{
    use HasFactory;

    protected $table = 'evenements';

    protected $fillable = [
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'statut',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    // Relations
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'evenement_id');
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class, 'evenement_id');
    }

    // Méthodes de calcul
    public function totalCotisations()
    {
        return $this->cotisations()->where('statut', 'actif')->sum('montant');
    }

    public function totalDepenses()
    {
        return $this->depenses()->where('statut', 'actif')->sum('montant');
    }

    public function solde()
    {
        return $this->totalCotisations() - $this->totalDepenses();
    }

    // Méthodes helper
    public function estTermine()
    {
        return $this->statut === 'termine';
    }

    public function estActif()
    {
        return $this->statut === 'actif';
    }
}
