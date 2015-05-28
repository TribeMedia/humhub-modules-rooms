<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 3:29 AM
 */

class RoomPickerWidget extends HWidget
{

    /**
     * Id of input element which should replaced
     *
     * @var type
     */
    public $inputId = "";

    /**
     * JSON Search URL - default: browse/searchJson
     *
     * The token -keywordPlaceholder- will replaced by the current search query.
     *
     * @var String Url with -keywordPlaceholder-
     */
    public $roomSearchUrl = "";

    /**
     * Maximum rooms
     *
     * @var type
     */
    public $maxRooms = 10;

    /**
     * @var CModel the data model associated with this widget. (Optional)
     */
    public $model = null;

    /**
     * @var string the attribute associated with this widget. (Optional)
     * The name can contain square brackets (e.g. 'name[1]') which is used to collect tabular data input.
     */
    public $attribute = null;

    /**
     * Initial value
     * Comma separated list of room guids
     *
     * @var string
     */
    public $value = "";

    /**
     * Inits the User Picker
     *
     */
    public function init()
    {
        if ($this->roomSearchUrl == "")
            $this->roomSearchUrl = Yii::app()->getController()->createUrl('//rooms/browse/searchJson', array('keyword' => '-keywordPlaceholder-'));

        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/roompicker.js');
    }

    /**
     * Displays / Run the Widgets
     */
    public function run()
    {
        // Try to get current field value, when model & attribute attributes are specified.
        if ($this->model != null && $this->attribute != null) {
            $attribute = $this->attribute;
            $this->value = $this->model->$attribute;
        }

        $this->render('roomPicker', array(
            'roomSearchUrl' => $this->roomSearchUrl,
            'maxRooms' => $this->maxRooms,
            'currentValue' => $this->value,
            'inputId' => $this->inputId,
        ));
    }

}

?>
