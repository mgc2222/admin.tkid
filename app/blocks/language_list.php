<?php
/**
 * Created by PhpStorm.
 * User: Cristi
 * Date: 2/5/2019
 * Time: 12:48 PM
 */
?>
<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?php echo $trans['languages.select_items']?>
        <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <?php foreach ($webpage->languagesDdl as $lang){
            ?>
            <li class="<?php echo ($webpage->language->id==$lang->id) ? 'active': ''?>" style="display: block">
                <a>
                    <button type="submit" style="width: 100%; background-color: unset;border: unset;" value="<?php echo $lang->id;?>" name="language"><?php echo $lang->abbreviation_iso;?></button>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>