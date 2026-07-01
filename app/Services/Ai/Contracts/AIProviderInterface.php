<?php

namespace App\Services\Ai\Contracts;

interface AIProviderInterface
{
    /**
     * Envoie une conversation (liste de messages {role, content}) au
     * fournisseur IA et retourne la réponse texte de l'assistant.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    public function chat(array $messages): string;

    /**
     * Demande au fournisseur IA de générer un jeu de questions à choix
     * multiples au format JSON structuré, pour un sujet et une difficulté
     * donnés.
     *
     * @return array<int, array{question: string, explanation: string, options: array<int, array{label: string, is_correct: bool}>}>
     */
    public function generateQuizQuestions(string $subject, int $count, string $difficulty): array;
}
