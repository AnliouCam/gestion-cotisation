<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Utilisateur extends Authenticatable
{
    use HasFactory;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'telephone',
        'mot_de_passe',
        'role',
        'date_adhesion',
        'statut',
        'doit_changer_mot_de_passe',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected $casts = [
        'date_adhesion' => 'date',
        'doit_changer_mot_de_passe' => 'boolean',
    ];

    // Relations
    public function cotisations()
    {
        return $this->hasMany(Cotisation::class, 'utilisateur_id');
    }

    public function cotisationsCrees()
    {
        return $this->hasMany(Cotisation::class, 'cree_par');
    }

    public function depensesCrees()
    {
        return $this->hasMany(Depense::class, 'cree_par');
    }

    // Méthodes helper
    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    public function estActif()
    {
        return $this->statut === 'actif';
    }

    // Override pour l'authentification
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}
