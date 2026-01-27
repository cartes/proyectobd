<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'height',
        'body_type',
        'relationship_status',
        'children',
        'education',
        'occupation',
        'income_range',
        'net_worth',
        'interests',
        'languages',
        'lifestyle',
        'looking_for',
        'availability',
        // Campos específicos Sugar Daddy
        'industry',
        'company_size',
        'travel_frequency',
        'what_i_offer',
        'mentorship_areas',
        // Campos específicos Sugar Baby
        'appearance_details',
        'personal_style',
        'fitness_level',
        'aspirations',
        'ideal_daddy',
        'is_private',
        'social_instagram',
        'social_whatsapp',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'languages' => 'array',
            'lifestyle' => 'array',
            'mentorship_areas' => 'array',
            'is_private' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Métodos existentes...
    public static function bodyTypes(): array
    {
        return [
            'delgado' => 'Delgado',
            'atletico' => 'Atlético',
            'promedio' => 'Promedio',
            'robusto' => 'Robusto',
            'corpulento' => 'Corpulento',
        ];
    }

    public static function relationshipStatuses(): array
    {
        return [
            'soltero' => 'Soltero/a',
            'divorciado' => 'Divorciado/a',
            'separado' => 'Separado/a',
            'viudo' => 'Viudo/a',
            'complicado' => 'Es complicado',
        ];
    }

    public static function childrenOptions(): array
    {
        return [
            'no_tengo' => 'No tengo hijos',
            'tengo_viven_conmigo' => 'Tengo hijos y viven conmigo',
            'tengo_no_viven' => 'Tengo hijos pero no viven conmigo',
            'quiero_tener' => 'Quiero tener hijos',
            'no_quiero' => 'No quiero tener hijos',
        ];
    }

    public static function educationLevels(): array
    {
        return [
            'secundaria' => 'Secundaria',
            'tecnico' => 'Técnico',
            'universitario' => 'Universitario',
            'postgrado' => 'Postgrado',
            'maestria' => 'Maestría',
            'doctorado' => 'Doctorado',
        ];
    }

    public static function incomeRanges(): array
    {
        return [
            'menos_50k' => 'Menos de $50,000',
            '50k_100k' => '$50,000 - $100,000',
            '100k_250k' => '$100,000 - $250,000',
            '250k_500k' => '$250,000 - $500,000',
            'mas_500k' => 'Más de $500,000',
            'prefiero_no_decir' => 'Prefiero no decirlo',
        ];
    }

    public static function availabilityOptions(): array
    {
        return [
            'muy_disponible' => 'Muy disponible',
            'fines_semana' => 'Fines de semana',
            'ocasional' => 'Ocasionalmente',
            'flexible' => 'Flexible',
            'limitada' => 'Disponibilidad limitada',
        ];
    }

    public static function interestsOptions(): array
    {
        return [
            'viajes' => 'Viajes',
            'gastronomia' => 'Gastronomía',
            'arte' => 'Arte y cultura',
            'musica' => 'Música',
            'deportes' => 'Deportes',
            'fitness' => 'Fitness',
            'moda' => 'Moda',
            'tecnologia' => 'Tecnología',
            'negocios' => 'Negocios',
            'lectura' => 'Lectura',
            'cine' => 'Cine y series',
            'naturaleza' => 'Naturaleza',
            'lujo' => 'Estilo de vida de lujo',
            'fiesta' => 'Vida nocturna',
        ];
    }

    // Nuevos métodos para campos específicos
    public static function industries(): array
    {
        return [
            'tecnologia' => 'Tecnología',
            'finanzas' => 'Finanzas',
            'inmobiliario' => 'Inmobiliario',
            'salud' => 'Salud',
            'legal' => 'Legal',
            'consultoria' => 'Consultoría',
            'emprendimiento' => 'Emprendimiento',
            'entretenimiento' => 'Entretenimiento',
            'manufactura' => 'Manufactura',
            'retail' => 'Retail/Comercio',
            'educacion' => 'Educación',
            'otro' => 'Otro',
        ];
    }

    public static function companySizes(): array
    {
        return [
            'startup' => 'Startup (1-10 empleados)',
            'pequena' => 'Pequeña (11-50 empleados)',
            'mediana' => 'Mediana (51-200 empleados)',
            'grande' => 'Grande (201-1000 empleados)',
            'corporativo' => 'Corporativo (1000+ empleados)',
            'emprendedor' => 'Emprendedor/Independiente',
        ];
    }

    public static function travelFrequencies(): array
    {
        return [
            'semanal' => 'Semanalmente',
            'mensual' => 'Mensualmente',
            'trimestral' => 'Cada 3 meses',
            'ocasional' => 'Ocasionalmente',
            'no_viajo' => 'No viajo frecuentemente',
        ];
    }

    public static function mentorshipAreasOptions(): array
    {
        return [
            'negocios' => 'Negocios y emprendimiento',
            'finanzas' => 'Finanzas personales',
            'carrera' => 'Desarrollo de carrera',
            'networking' => 'Networking',
            'inversion' => 'Inversiones',
            'liderazgo' => 'Liderazgo',
            'tecnologia' => 'Tecnología',
            'marketing' => 'Marketing',
        ];
    }

    public static function personalStyles(): array
    {
        return [
            'elegante' => 'Elegante y sofisticada',
            'casual' => 'Casual y relajada',
            'deportivo' => 'Deportivo y activa',
            'bohemio' => 'Bohemio y artístico',
            'glamoroso' => 'Glamoroso y fashionista',
            'minimalista' => 'Minimalista y moderno',
        ];
    }

    public static function fitnessLevels(): array
    {
        return [
            'muy_activo' => 'Muy activa - Ejercicio diario',
            'activo' => 'Activa - 3-4 veces por semana',
            'moderado' => 'Moderadamente activa',
            'ligero' => 'Actividad ligera',
            'sedentario' => 'Poco activa',
        ];
    }
}
