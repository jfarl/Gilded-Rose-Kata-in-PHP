<?php

namespace App;

class Item
{
    // Once the sell by date has passed, Quality degrades twice as fast
    // The Quality of an item is never negative
    public $name;
    public $quality;
    public $sellIn;

    public function __construct($name, $quality, $sellIn)
    {
        $this->name = $name;
        $this->quality = $quality;
        $this->sellIn = $sellIn;
    }

    public function degradeQuality($amount) {
        if ( ($this->quality - $amount) >= 0) {
            $this->quality -= $amount;
        }
        else {
            $this->quality = 0;
        }
    }

    public function tick()
    {
        $this->sellIn -= 1;

        $this->degradeQuality(1);

        if ($this->sellIn < 0 ) {
            $this->degradeQuality(1);
        }
            
    }
} // Item

class AgedBrie extends Item
{   
    public function increaseQuality($amount) {
        if ( ($this->quality + $amount) <= 50) {
            $this->quality += $amount;
        } 
        else {
            $this->quality = 50;
        }
    }

    public function tick()
    {
        // - The Quality of an item is never negative
        // - The Quality of an item is never more than 50
        // "Aged Brie" actually increases in Quality the older it gets
        $this->sellIn -= 1;
        
        $this->increaseQuality(1);

        if ($this->sellIn < 0 ) {
            $this->increaseQuality(1);
        }
       
    }

} // AgedBrie

class BackstagePasses extends AgedBrie
{
    //"Backstage passes", like aged brie, increases in Quality as it's SellIn value approaches; Quality increases by 2 when there are 10 days or less and by 3 when there are 5 days or less but Quality drops to 0 after the concert

    public function tick()
    {
        $this->sellIn -= 1;

        $this->increaseQuality(1);

        if ($this->sellIn < 10 && $this->sellIn > 5 ) {
            $this->increaseQuality(1);
        }
        if ($this->sellIn <= 5) {
            $this->increaseQuality(2);
        }
        if ($this->sellIn < 0 ) {
            $this->quality = 0;
        }

    }

} // BackstagePasses

class Sulfuras extends Item
{
    // "Sulfuras", being a legendary item, never has to be sold or decreases in Quality
    // "Sulfuras" is a legendary item and as such its Quality is 80 and it never alters.
    public function tick()
    {
        
    }

} // Sulfuras


class Conjured extends Item
{
    // "Conjured" items degrade in Quality twice as fast as normal items
    public function tick()
    {
        $this->sellIn -= 1;

        if ($this->sellIn < 0 ) {
            $this->degradeQuality(4);
        }
        else {
            $this->degradeQuality(2);
        }
    }

} // Conjured


class GildedRose
{
    public static function of($name, $quality, $sellIn) {
        if ($name == "normal") {
            return new Item($name, $quality, $sellIn);
        }
        if ($name == "Aged Brie") {
            return new AgedBrie($name, $quality, $sellIn);
        }
        if ($name == "Sulfuras, Hand of Ragnaros") {
            return new Sulfuras($name, $quality, $sellIn);
        }
        if ($name == "Backstage passes to a TAFKAL80ETC concert") {
            return new BackstagePasses($name, $quality, $sellIn);
        }
        if ($name == "Conjured Mana Cake") {
            return new Conjured($name, $quality, $sellIn);
        }  
    }
}
