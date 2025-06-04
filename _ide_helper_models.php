<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AppConfig
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AppConfigFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AppConfig whereValue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAppConfig {}
}

namespace App\Models{
/**
 * App\Models\Appointment
 *
 * @property int $id
 * @property int|null $patient_id
 * @property string|null $date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \App\Models\Patient|null $patient
 * @method static \Database\Factories\AppointmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appointment whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperAppointment {}
}

namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBaseModel {}
}

namespace App\Models{
/**
 * App\Models\DeletedPatient
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $age
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $file_number
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int|null $gender
 * @property int|null $total_amount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeletedPatient whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDeletedPatient {}
}

namespace App\Models{
/**
 * App\Models\Expense
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $description
 * @property string|null $name
 * @property string|null $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @method static \Database\Factories\ExpenseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expense whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperExpense {}
}

namespace App\Models{
/**
 * App\Models\Medication
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $manufacturer
 * @property string|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Patient> $patients
 * @property-read int|null $patients_count
 * @method static \Database\Factories\MedicationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Medication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medication query()
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medication whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedication {}
}

namespace App\Models{
/**
 * App\Models\Patient
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $age
 * @property string|null $phone
 * @property string|null $mobile
 * @property int|null $file_number
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int|null $gender
 * @property int|null $total_amount
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientRecordTooth> $affectedTeeth
 * @property-read int|null $affected_teeth_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientRecord> $diagnosis
 * @property-read int|null $diagnosis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientFile> $files
 * @property-read int|null $files_count
 * @property-read \App\Models\Visit|null $lastVisit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medication> $medications
 * @property-read int|null $medications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientRecord> $records
 * @property-read int|null $records_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientRecord> $symptoms
 * @property-read int|null $symptoms_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Visit> $visits
 * @property-read int|null $visits_count
 * @method static \Database\Factories\PatientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereFileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Patient withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatient {}
}

namespace App\Models{
/**
 * App\Models\PatientFile
 *
 * @property int $id
 * @property int $patient_id
 * @property int $user_id
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property string|null $file_name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PatientFileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientFile withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatientFile {}
}

namespace App\Models{
/**
 * App\Models\PatientRecord
 *
 * @property int $id
 * @property int $patient_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $treatment
 * @property string|null $notes
 * @property string|null $next_visit_date
 * @property int|null $user_id
 * @property string|null $symptoms
 * @property string|null $diagnosis
 * @property int $record_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tooth> $affectedTeeth
 * @property-read int|null $affected_teeth_count
 * @property-read \App\Models\Patient $patient
 * @method static \Database\Factories\PatientRecordFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereNextVisitDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereRecordDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereTreatment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecord whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatientRecord {}
}

namespace App\Models{
/**
 * App\Models\PatientRecordTooth
 *
 * @property int $id
 * @property int $patient_record_id
 * @property int $tooth_id
 * @property int $is_treated
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth query()
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth whereIsTreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth wherePatientRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PatientRecordTooth whereToothId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPatientRecordTooth {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property int $id
 * @property string|null $amount
 * @property string|null $remaining_amount
 * @property string $date
 * @property int $patient_id
 * @property int $visit_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $user_id
 * @property int $status
 * @property int|null $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Payment> $children
 * @property-read int|null $children_count
 * @property-read Payment|null $parent
 * @property-read \App\Models\Patient $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServicePayment> $servicePayment
 * @property-read int|null $service_payment_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Service> $services
 * @property-read int|null $services_count
 * @property-read \App\Models\Visit $visit
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereRemainingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereVisitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withLatestPayment()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withTotalRemainingAmount()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPayment {}
}

namespace App\Models{
/**
 * App\Models\Permission
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPermission {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRole {}
}

namespace App\Models{
/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $duration Duration in minutes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payment
 * @property-read int|null $payment_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServicePayment> $servicePayment
 * @property-read int|null $service_payment_count
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperService {}
}

namespace App\Models{
/**
 * App\Models\ServicePayment
 *
 * @property int $id
 * @property int $service_id
 * @property int $payment_id
 * @property string $price
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @property-read \App\Models\Service $service
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServicePayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperServicePayment {}
}

namespace App\Models{
/**
 * App\Models\Tooth
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $image
 * @property int|null $number
 * @property array|null $extra
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PatientRecord> $patientRecords
 * @property-read int|null $patient_records_count
 * @method static \Database\Factories\ToothFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooth whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTooth {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $admin
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 * App\Models\Visit
 *
 * @property int $id
 * @property int $patient_id
 * @property string $date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $user_id
 * @property-read int|null $amount
 * @property-read \App\Models\Patient $patient
 * @property-read \App\Models\Payment|null $payment
 * @method static \Database\Factories\VisitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Visit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Visit withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVisit {}
}

