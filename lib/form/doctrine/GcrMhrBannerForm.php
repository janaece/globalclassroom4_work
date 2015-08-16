<?php

/**
 * banner edit form, located on the eSchool customization page
 *
 * @package    globalclassroom
 * @subpackage form
 * @author     Steven Nelson
 */
class GcrMhrBannerForm extends BaseGcrInstitutionForm
{
	public function configure()
	{
		$this->setWidgets
		(
			array
			(
				'logo' => new sfWidgetFormInputFile(),
			)
		);

		$this->widgetSchema->setLabel('logo', ' ');

		$this->setValidators(
			array
			(
				'logo' => new sfValidatorFile
					(
						array
							(
								'required' => false,
								'path' => gcr::moodledataDir . $this->getObject()->getShortName() . '/gc_images/',
								'mime_types' => 'web_images'
							)
					)
			)
		);

	}
}
