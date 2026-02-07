<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    use HasFactory;

    protected $table = 'depenses';

    protected $fillable = [
        'evenement_id',
        'categorie_id',
        'montant',
        'description',
        'justification',
        'statut',
        'motif_annulation',
        'annule_le',
        'cree_par',
    ];

    protected $casts = [
        'annule_le' => 'datetime',
        'montant' => 'decimal:2',
    ];

    // RELATIONS

    /**
     * La dépense est liée à un événement
     */
    public function evenement()
    {
        return $this->belongsTo(Evenement::class, 'evenement_id');
    }

    /**
     * La dépense appartient à une catégorie (Achat, Location, Aide, etc.)
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    /**
     * Traçabilité : qui a enregistré cette dépense
     */
    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    // MÉTHODES POUR L'ANNULATION

    /**
     * Annuler une dépense (ne jamais supprimer, seulement annuler)
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
     * Vérifie si la dépense peut être annulée
     * On ne peut annuler que si l'événement est encore actif
     */
    public function peutEtreAnnulee()
    {
        return $this->statut === 'actif' && $this->evenement->estActif();
    }

    // MÉTHODES HELPER

    /**
     * Vérifie si la dépense est active (compte dans les calculs)
     */
    public function estActive()
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifie si la dépense est annulée
     */
    public function estAnnulee()
    {
        return $this->statut === 'annule';
    }
}
