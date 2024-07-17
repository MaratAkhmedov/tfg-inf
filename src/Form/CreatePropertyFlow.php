<?php
namespace App\Form;

use Craue\FormFlowBundle\Event\PostBindSavedDataEvent;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowEvents;
use Craue\FormFlowBundle\Form\FormFlowInterface;

class CreatePropertyFlow extends FormFlow {
	protected $allowDynamicStepNavigation = true;
	protected $handleFileUploadsTempDir = '/public/uploads/tmp/';
    public $myImages = [];
	protected function loadStepsConfig() {
		return [
			[
				'label' => 'initial',
				'form_type' => PropertyType::class,
			],
            [
				'label' => 'address',
				'form_type' => PropertyType::class,
				// 'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
				// 	return $estimatedCurrentStepNumber > 1;
				// },
			],
            [
				'label' => 'property_details',
				'form_type' => PropertyType::class,
				// 'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
				// 	return $estimatedCurrentStepNumber > 1;
				// },
			],
            [
				'label' => 'room_data',
				'form_type' => PropertyType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber >= 1 
                        && $flow->getFormData()->getType() 
                        && $flow->getFormData()->getType()->getName() == 'flat';
				},
			],
            [
				'label' => 'image_upload',
                'form_type' => PropertyType::class,
			],
			[
				'label' => 'confirmation',
			],
		];
	}

    /**
     * If this is set to true then form data for the current
     * step will be saved when transitioning backwards.
     *
     * @var bool
     */
    protected $persistOnBackTransition = true;

    /**
     * @param int $fromStepNumber
     */
    public function invalidateStepData($fromStepNumber)
    {
        // Do nothing, this prevents us from deleting step data when user hits "back" button. See @Adam314 solution.
    }

    public function getImages()
    {
        return $this->myImages;
    }

    /**
     * {@inheritdoc}
     */
    protected function bindFlow() {
        parent::bindFlow();

        if ($this->persistOnBackTransition && $this->getCurrentStepNumber() == 5) {
            $currentStepNumber = $this->getCurrentStepNumber() + 1;
            $form = $this->createFormForStep($currentStepNumber);
            $stepData = $this->retrieveStepData();
            $request = $this->getRequest();
            $formName = $form->getName();
            //$currentStepData = $request->request->get($formName, []);
            $this->myImages = $stepData[5]["images"];
            $test = file_get_contents($stepData[5]["images"]);

            //$stepData[$currentStepNumber] = $currentStepData;
            //$this->saveStepData($stepData);
        }
    }

}