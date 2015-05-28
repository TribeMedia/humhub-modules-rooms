<?php
/**
 * This View replaces an input with a room picker
 *
 * @property String $inputId is the ID of the input HTML Element
 * @property Int $maxRooms the maximum of rooms for this input
 * @property String $roomSearchUrl the url of the search, to find the rooms
 * @property String $currentValue is the current value of the parent field.
 *
 * @package humhub.modules_core.user
 * @since 0.5
 */
?>

<?php

// Resolve guids to room tags
$newValue = "";

foreach (explode(",", $currentValue) as $guid) {
    $room = Room::model()->findByAttributes(array('guid' => trim($guid)));
    if ($room != null) {
        $imageUrl = $room->getProfileImage()->getUrl();
        $name = CHtml::encode($room->name);
        $newValue .= '<li class="roomInput" id="' . $room->guid . '"><img class="img-rounded" alt="24x24" data-src="holder.js/24x24" style="width: 24px; height: 24px;" src="' . $imageUrl . '" alt="' . $name . 'r" width="24" height="24">' . addslashes($name) . '<i class="fa fa-times-circle"></i></li>';

    }
}
?>


<script type="text/javascript">

    $('#<?php echo $inputId; ?>').roompicker({
        inputId: '#<?php echo $inputId; ?>',
        maxRooms: '<?php echo $maxRooms; ?>',
        searchUrl: '<?php echo $roomSearchUrl; ?>',
        currentValue: '<?php echo $newValue; ?>'
    });

</script>