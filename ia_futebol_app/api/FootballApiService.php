<?php
if (!defined('APP_ROOT')) { die('Acesso direto não permitido.'); }

class FootballApiService
{
    private $apiKey;
    private $baseUrl = "https://api.sportsbrapi.com/api";

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getTodayMatches()
    {
        if (empty($this->apiKey)) {
            return ['matches' => $this->getMockMatches(), 'isDemoMode' => true];
        }
        // Futuramente, a lógica de chamada cURL à API real irá aqui
        return ['matches' => $this->getMockMatches(), 'isDemoMode' => true];
    }
    
    private function getMockMatches(): array
    {
        return [
            [
                'fixture' => ['id' => 1, 'date' => '2025-10-15T22:09:00Z', 'status' => ['short' => 'NS'], 'venue' => ['name' => 'Estádio do Maracanã']],
                'league' => ['name' => 'Brasileirão Série A', 'logo' => '/assets/img/vibrant-football-league.png'],
                'teams' => ['home' => ['name' => 'Flamengo', 'logo' => 'https://media.api-sports.io/football/teams/127.png'], 'away' => ['name' => 'Corinthians', 'logo' => 'https://media.api-sports.io/football/teams/131.png']]
            ],
            [
                'fixture' => ['id' => 2, 'date' => '2025-10-16T19:00:00Z', 'status' => ['short' => 'NS'], 'venue' => ['name' => 'Old Trafford']],
                'league' => ['name' => 'Premier League', 'logo' => '/assets/img/vibrant-football-league.png'],
                'teams' => ['home' => ['name' => 'Manchester United', 'logo' => 'https://media.api-sports.io/football/teams/33.png'], 'away' => ['name' => 'Liverpool', 'logo' => 'https://media.api-sports.io/football/teams/40.png']]
            ],
        ];
    }
}
?>