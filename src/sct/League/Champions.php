<?php

namespace sct\League;

use sct\League\Exception\ChampionDoesNotExistException;

class Champions
{
    /**
     * Array containing all the currently loaded champions
     *
     * @var array
     */
    private static $champions = array();

    /**
     * Boolean to track whether or not champions are currently loaded
     *
     * @var boolean
     */
    private static $isLoaded = false;

    /**
     * Loads champions from the API into the champions array
     * @param string $region Region to load from
     * @param string $key    API Key
     */
    public static function loadChampions($region, $key)
    {
        $league    = new League($key, $region);
        $champions = $league->getChampions();

        foreach ($champions['champions'] as $champion) {
            self::addChampion($champion);
        }

        self::$isLoaded = true;
    }

    /**
     * Adds a champion into the champions Array
     *
     * @param Array $champion Champion Array
     */
    public static function addChampion($champion)
    {
        $champion = new Champion($champion['id'], $champion['name']);

        $champion->setActive($champion['active'])
                ->setBotEnabled($champion['botEnabled'])
                ->setBotMmEnabled($champion['botMmEnabled'])
                ->setRanks($champion['attackRank'], $champion['defenseRank'], $champion['magicRank'], $champion['difficultyRank'])
                ->setRankedPlayEnabled($champion['rankedPlayEnabled']);

        self::$champions[$champion['name']] = $champion;
    }

    /**
     * Returns the current array of champions
     *
     * @return Array Champions array
     */
    public static function getChampions()
    {
        return self::$champions;
    }

    /**
     * Request a specific champion from the champions array. Throws an exception
     * if it does not exist.
     *
     * @param string $name Champion Name
     *
     * @return array Champion Array
     */
    public static function getChampion($name)
    {
        if (!array_key_exists($name, self::$champions)) {
            throw new ChampionDoesNotExistException('Champion does not exist');
        }

        return self::$champions[$name];
    }

    /**
     * Returns true if there is an actively loaded champions array
     *
     * @return boolean
     */
    public static function isLoaded()
    {
        return self::$isLoaded;
    }
}
