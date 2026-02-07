<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;

    protected $table = 'cotisations';

    protected $fillable = [
        'utilisateur_id',
        'evenement_id',
        'montant',
        'date',
        'commentaire',
        'statut',
        'motif_annulation',
        'annule_le',
        'cree_par',
    ];

    protected $casts = [
        'date' => 'date',
        'annule_le' => 'datetime',
        'montant' => 'decimal:2',
    ];

    // RELATIONS

    /**
     * La cotisation appartient à un utilisateur (le membre qui a cotisé)
     */
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    /**
     * La cotisation est liée à un événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }

    /**
     * Traçabilité : qui a enregistré cette cotisation
     */
    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    // MÉTHODES POUR L'ANNULATION

    /**
     * Annuler une cotisation (ne jamais supprimer, seulement annuler)
     * @param string $motif - Raison de l'annulation
     */
    public function annuler($motif = null)
    {
        $this->statut = 'annule';
        $this->motif_annulation = $motif;
        $this->annule_le = now();
        $this->save();
    }

    /**
     * Vérifie si la cotisation peut être annulée
     * On ne peut annuler que si l'événement est encore actif
     */
    public function peutEtreAnnulee()
    {
        return $this->statut === 'actif' && $this->evenement->estActif();
    }

    // MÉTHODES HELPER

    /**
     * Vérifie si la cotisation est active (compte dans les calculs)
     */
    public function estActive()
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifie si la cotisation est annulée
     */
    public function estAnnulee()
    {
        return $this->statut === 'annule';
    }
}
