<?php

namespace App;

interface BlogInterface
{
	public function add($data);

	public function delete($data);

	public function update($data);
}