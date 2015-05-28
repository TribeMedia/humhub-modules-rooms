<?php
/**
 * Created by PhpStorm.
 * User: gqadonis
 * Date: 5/28/15
 * Time: 3:16 AM
 */

class RoomModelModulesBehavior extends CActiveRecordBehavior
{

    public $_enabledModules = null;
    public $_availableModules = null;

    /**
     * Collects a list of all modules which are available for this room
     *
     * @return array
     */
    public function getAvailableModules()
    {

        if ($this->_availableModules !== null) {
            return $this->_availableModules;
        }

        $this->_availableModules = array();

        foreach (Yii::app()->moduleManager->getEnabledModules() as $moduleId => $module) {
            if ($module->isRoomModule()) {
                $this->_availableModules[$module->getId()] = $module;
            }
        }

        return $this->_availableModules;
    }

    /**
     * Returns an array of enabled room modules
     *
     * @return array
     */
    public function getEnabledModules()
    {

        if ($this->_enabledModules !== null) {
            return $this->_enabledModules;
        }

        $this->_enabledModules = array();

        $availableModules = $this->getAvailableModules();
        $defaultStates = RoomApplicationModule::getStates();
        $states = RoomApplicationModule::getStates($this->getOwner()->id);

        // Get a list of all enabled module ids
        foreach (array_merge(array_keys($defaultStates), array_keys($states)) as $id) {

            // Ensure module Id is available
            if (!array_key_exists($id, $availableModules)) {
                continue;
            }

            if (isset($defaultStates[$id]) && $defaultStates[$id] == RoomApplicationModule::STATE_FORCE_ENABLED) {
                // Forced enabled globally
                $this->_enabledModules[] = $id;
            } elseif (!isset($states[$id]) && isset($defaultStates[$id]) && $defaultStates[$id] == RoomApplicationModule::STATE_ENABLED) {
                // No local state -> global default on
                $this->_enabledModules[] = $id;
            } elseif (isset($states[$id]) && $states[$id] == RoomApplicationModule::STATE_ENABLED) {
                // Locally enabled
                $this->_enabledModules[] = $id;
            }
        }

        return $this->_enabledModules;
    }

    /**
     * Checks if given ModuleId is enabled
     *
     * @param type $moduleId
     */
    public function isModuleEnabled($moduleId)
    {
        return in_array($moduleId, $this->getEnabledModules());
    }

    /**
     * Enables a Module
     */
    public function enableModule($moduleId)
    {

        // Not enabled globally
        if (!array_key_exists($moduleId, $this->getAvailableModules())) {
            return false;
        }

        // Already enabled module
        if ($this->isModuleEnabled($moduleId)) {
            Yii::log("Room->enableModule(" . $moduleId . ") module is already enabled");
            return false;
        }

        // Add Binding
        $roomModule = RoomApplicationModule::model()->findByAttributes(array('room_id' => $this->getOwner()->id, 'module_id' => $moduleId));
        if ($roomModule == null) {
            $roomModule = new RoomApplicationModule();
            $roomModule->room_id = $this->getOwner()->id;
            $roomModule->module_id = $moduleId;
        }
        $roomModule->state = RoomApplicationModule::STATE_ENABLED;
        $roomModule->save();

        $module = Yii::app()->moduleManager->getModule($moduleId);
        $module->enableRoomModule($this->getOwner());

        return true;
    }

    public function canDisableModule($id)
    {
        $defaultStates = RoomApplicationModule::getStates(0);
        if (isset($defaultStates[$id]) && $defaultStates[$id] == RoomApplicationModule::STATE_FORCE_ENABLED) {
            return false;
        }

        return true;
    }

    /**
     * Uninstalls a Module
     */
    public function disableModule($moduleId)
    {

        // Not enabled globally
        if (!array_key_exists($moduleId, $this->getAvailableModules())) {
            return false;
        }

        // Already enabled module
        if (!$this->isModuleEnabled($moduleId)) {
            Yii::log("Room->disableModule(" . $moduleId . ") module is not enabled");
            return false;
        }

        // New Way: Handle it directly in module class
        $module = Yii::app()->moduleManager->getModule($moduleId);
        $module->disableRoomModule($this->getOwner());

        $roomModule = RoomApplicationModule::model()->findByAttributes(array('room_id' => $this->getOwner()->id, 'module_id' => $moduleId));
        if ($roomModule == null) {
            $roomModule = new RoomApplicationModule();
            $roomModule->room_id = $this->getOwner()->id;
            $roomModule->module_id = $moduleId;
        }
        $roomModule->state = RoomApplicationModule::STATE_DISABLED;
        $roomModule->save();

        return true;
    }

}
