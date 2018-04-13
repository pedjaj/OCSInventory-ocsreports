<?php
/*
 * Copyright 2005-2016 OCSInventory-NG/OCSInventory-ocsreports contributors.
 * See the Contributors file for more details about them.
 *
 * This file is part of OCSInventory-NG/OCSInventory-ocsreports.
 *
 * OCSInventory-NG/OCSInventory-ocsreports is free software: you can redistribute
 * it and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 2 of the License,
 * or (at your option) any later version.
 *
 * OCSInventory-NG/OCSInventory-ocsreports is distributed in the hope that it
 * will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OCSInventory-NG/OCSInventory-ocsreports. if not, write to the
 * Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

require("require/search/DatabaseSearch.php");
require("require/search/AccountinfoSearch.php");
require("require/search/TranslationSearch.php");
require("require/search/LegacySearch.php");
require("require/search/Search.php");

// Get tables and columns infos
$databaseSearch = new DatabaseSearch();

// Get columns infos datamap structure
$accountInfoSearch = new AccountinfoSearch();

// Get columns infos datamap structure
$translationSearch = new TranslationSearch();

// Get search object to perform action and show result
//$legacySearch = new LegacySearch();

//var_dump($databaseSearch->getColumnsList('cpus'));

$search = new Search($translationSearch, $databaseSearch, $accountinfoSearch);

if (isset($protectedPost['table_select'])) {
	$defaultTable = $protectedPost['table_select'];
} else {
	$defaultTable = null;
}

var_dump($protectedPost);

?>
<div class="panel panel-default">

	<?php printEnTete($l->g(9)); ?>

	<div class="row">
		<div class="col-md-12">


			<?php echo open_form('addSearchCrit', '', '', '') ?>

			<div class="row">
				<div class="col-sm-2"></div>
				<div class="col-sm-3">
					<div class="form-group">
						<select class="form-control" name="table_select" onchange="this.form.submit()">
							<?php echo $search->getSelectOptionForTables($defaultTable)  ?>
						</select>
					</div> 
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<select class="form-control" name="columns_select">
							<?php 
								if (!is_null($defaultTable)) {
									echo $search->getSelectOptionForColumns($defaultTable);
								}
							?>
						</select>
					</div> 
				</div>
				<div class="col-sm-3">
					<input type="submit" class="btn btn-info" value="<?php echo $l->g(116) ?>">
				</div>
			</div> 

			<input name="old_table" type="hidden" value="<?php echo $defaultTable ?>">

			<?php echo close_form(); ?>

		</div>
	</div>
</div>

<?php 

// Add var to session datamap
if (isset($protectedPost['old_table']) && isset($protectedPost['table_select'])) {
	if ($protectedPost['old_table'] === $protectedPost['table_select']) {
		$search->addSessionsInfos($protectedPost);
	}
}

?>
<div name="multiSearchCritsDiv">
<?php

echo open_form('multiSearchCrits', '', '', '');

if (!empty($_SESSION['OCS']['multi_search'])) {
	foreach ($_SESSION['OCS']['multi_search'] as $table => $infos) {
		foreach ($infos as $uniqid => $values) {
			?>
			<div class="row" name="<?php echo $uniqid ?>">
				<div class="col-sm-3">
					<span class="label label-info"><?php 
						echo $search->getTranslationFor($table)." : ".$search->getTranslationFor($values['fields']);
					?></span>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<select class="form-control" name="<?php echo $search->getOperatorUniqId($uniqid); ?>">
							<?php echo $search->getSelectOptionForOperators($values['operator'])  ?>
						</select>
					</div> 
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<?php echo $search->returnFieldHtml($uniqid, $values, $table) ?>
					</div> 
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<button type="button" class="btn btn-danger" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div> 
				</div>
			</div>
			<?php	
		}
	}
}

?>

<div class="col-sm-12">
	<input type="submit" class="btn btn-success" value="<?php echo $l->g(13) ?>">
</div>

<?php

echo close_form();

?>
</div>
