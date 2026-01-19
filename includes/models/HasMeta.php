<?php
interface HasMeta {
    public function getMetaTable(): string;
    public function getPrimaryKey(): string;
}