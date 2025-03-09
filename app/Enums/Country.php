<?php

namespace App\Enums;

use App\Enums\Concerns\InteractsWithEnums;

enum Country: string
{
    use InteractsWithEnums;

    case AD = 'Andorra';
    case AE = 'United Arab Emirates';
    case AF = 'Afghanistan';
    case AG = 'Antigua and Barbuda';
    case AI = 'Anguilla';
    case AL = 'Albania';
    case AM = 'Armenia';
    case AO = 'Angola';
    case AQ = 'Antarctica';
    case AR = 'Argentina';
    case AS = 'American Samoa';
    case AT = 'Austria';
    case AU = 'Australia';
    case AW = 'Aruba';
    case AX = 'Åland Islands';
    case AZ = 'Azerbaijan';
    case BA = 'Bosnia and Herzegovina';
    case BB = 'Barbados';
    case BD = 'Bangladesh';
    case BE = 'Belgium';
    case BF = 'Burkina Faso';
    case BG = 'Bulgaria';
    case BH = 'Bahrain';
    case BI = 'Burundi';
    case BJ = 'Benin';
    case BL = 'St. Barthélemy';
    case BM = 'Bermuda';
    case BN = 'Brunei';
    case BO = 'Bolivia';
    case BQ = 'Caribbean Netherlands';
    case BR = 'Brazil';
    case BS = 'Bahamas';
    case BT = 'Bhutan';
    case BV = 'Bouvet Island';
    case BW = 'Botswana';
    case BY = 'Belarus';
    case BZ = 'Belize';
    case CA = 'Canada';
    case CC = 'Cocos (Keeling) Islands';
    case CD = 'Democratic Republic of the Congo';
    case CF = 'Central African Republic';
    case CG = 'Republic of the Congo';
    case CH = 'Switzerland';
    case CI = 'Côte d\'Ivoire';
    case CK = 'Cook Islands';
    case CL = 'Chile';
    case CM = 'Cameroon';
    case CN = 'China';
    case CO = 'Colombia';
    case CR = 'Costa Rica';
    case CU = 'Cuba';
    case CV = 'Cape Verde';
    case CW = 'Curaçao';
    case CX = 'Christmas Island';
    case CY = 'Cyprus';
    case CZ = 'Czechia';
    case DE = 'Germany';
    case DJ = 'Djibouti';
    case DK = 'Denmark';
    case DM = 'Dominica';
    case DO = 'Dominican Republic';
    case DZ = 'Algeria';
    case EC = 'Ecuador';
    case EE = 'Estonia';
    case EG = 'Egypt';
    case EH = 'Western Sahara';
    case ER = 'Eritrea';
    case ES = 'Spain';
    case ET = 'Ethiopia';
    case FI = 'Finland';
    case FJ = 'Fiji';
    case FK = 'Falkland Islands';
    case FM = 'Micronesia';
    case FO = 'Faroe Islands';
    case FR = 'France';
    case GA = 'Gabon';
    case GB = 'United Kingdom';
    case GD = 'Grenada';
    case GE = 'Georgia';
    case GF = 'French Guiana';
    case GG = 'Guernsey';
    case GH = 'Ghana';
    case GI = 'Gibraltar';
    case GL = 'Greenland';
    case GM = 'Gambia';
    case GN = 'Guinea';
    case GP = 'Guadeloupe';
    case GQ = 'Equatorial Guinea';
    case GR = 'Greece';
    case GS = 'South Georgia & South Sandwich Islands';
    case GT = 'Guatemala';
    case GW = 'Guinea-Bissau';
    case GY = 'Guyana';
    case HK = 'Hong Kong';
    case HM = 'Heard & McDonald Islands';
    case HN = 'Honduras';
    case HR = 'Croatia';
    case HT = 'Haiti';
    case HU = 'Hungary';
    case ID = 'Indonesia';
    case IE = 'Ireland';
    case IL = 'Israel';
    case IM = 'Isle of Man';
    case IN = 'India';
    case IO = 'British Indian Ocean Territory';
    case IQ = 'Iraq';
    case IR = 'Iran';
    case IS = 'Iceland';
    case IT = 'Italy';
    case JE = 'Jersey';
    case JM = 'Jamaica';
    case JO = 'Jordan';
    case JP = 'Japan';
    case KE = 'Kenya';
    case KG = 'Kyrgyzstan';
    case KH = 'Cambodia';
    case KI = 'Kiribati';
    case KM = 'Comoros';
    case KN = 'Saint Kitts and Nevis';
    case KP = 'North Korea';
    case KR = 'South Korea';
    case KW = 'Kuwait';
    case KY = 'Cayman Islands';
    case KZ = 'Kazakhstan';
    case LA = 'Laos';
    case LB = 'Lebanon';
    case LC = 'Saint Lucia';
    case LI = 'Liechtenstein';
    case LK = 'Sri Lanka';
    case LR = 'Liberia';
    case LS = 'Lesotho';
    case LT = 'Lithuania';
    case LU = 'Luxembourg';
    case LV = 'Latvia';
    case LY = 'Libya';
    case MA = 'Morocco';
    case MC = 'Monaco';
    case MD = 'Moldova';
    case ME = 'Montenegro';
    case MF = 'St. Martin';
    case MG = 'Madagascar';
    case MH = 'Marshall Islands';
    case MK = 'North Macedonia';
    case ML = 'Mali';
    case MM = 'Myanmar';
    case MN = 'Mongolia';
    case MO = 'Macao';
    case MP = 'Northern Mariana Islands';
    case MQ = 'Martinique';
    case MR = 'Mauritania';
    case MS = 'Montserrat';
    case MT = 'Malta';
    case MU = 'Mauritius';
    case MV = 'Maldives';
    case MW = 'Malawi';
    case MX = 'Mexico';
    case MY = 'Malaysia';
    case MZ = 'Mozambique';
    case NA = 'Namibia';
    case NC = 'New Caledonia';
    case NE = 'Niger';
    case NF = 'Norfolk Island';
    case NG = 'Nigeria';
    case NI = 'Nicaragua';
    case NL = 'Netherlands';
    case NO = 'Norway';
    case NP = 'Nepal';
    case NR = 'Nauru';
    case NU = 'Niue';
    case NZ = 'New Zealand';
    case OM = 'Oman';
    case PA = 'Panama';
    case PE = 'Peru';
    case PF = 'French Polynesia';
    case PG = 'Papua New Guinea';
    case PH = 'Philippines';
    case PK = 'Pakistan';
    case PL = 'Poland';
    case PM = 'St. Pierre & Miquelon';
    case PN = 'Pitcairn Islands';
    case PR = 'Puerto Rico';
    case PS = 'Palestine';
    case PT = 'Portugal';
    case PW = 'Palau';
    case PY = 'Paraguay';
    case QA = 'Qatar';
    case RE = 'Réunion';
    case RO = 'Romania';
    case RS = 'Serbia';
    case RU = 'Russia';
    case RW = 'Rwanda';
    case SA = 'Saudi Arabia';
    case SB = 'Solomon Islands';
    case SC = 'Seychelles';
    case SD = 'Sudan';
    case SE = 'Sweden';
    case SG = 'Singapore';
    case SH = 'St. Helena';
    case SI = 'Slovenia';
    case SJ = 'Svalbard & Jan Mayen';
    case SK = 'Slovakia';
    case SL = 'Sierra Leone';
    case SM = 'San Marino';
    case SN = 'Senegal';
    case SO = 'Somalia';
    case SR = 'Suriname';
    case SS = 'South Sudan';
    case ST = 'São Tomé and Príncipe';
    case SV = 'El Salvador';
    case SX = 'Sint Maarten';
    case SY = 'Syria';
    case SZ = 'Eswatini';
    case TC = 'Turks & Caicos Islands';
    case TD = 'Chad';
    case TF = 'French Southern Territories';
    case TG = 'Togo';
    case TH = 'Thailand';
    case TJ = 'Tajikistan';
    case TK = 'Tokelau';
    case TL = 'Timor-Leste';
    case TM = 'Turkmenistan';
    case TN = 'Tunisia';
    case TO = 'Tonga';
    case TR = 'Turkey';
    case TT = 'Trinidad and Tobago';
    case TV = 'Tuvalu';
    case TW = 'Taiwan';
    case TZ = 'Tanzania';
    case UA = 'Ukraine';
    case UG = 'Uganda';
    case UM = 'U.S. Outlying Islands';
    case US = 'United States';
    case UY = 'Uruguay';
    case UZ = 'Uzbekistan';
    case VA = 'Vatican City';
    case VC = 'Saint Vincent and the Grenadines';
    case VE = 'Venezuela';
    case VG = 'British Virgin Islands';
    case VI = 'U.S. Virgin Islands';
    case VN = 'Vietnam';
    case VU = 'Vanuatu';
    case WF = 'Wallis & Futuna';
    case WS = 'Samoa';
    case YE = 'Yemen';
    case YT = 'Mayotte';
    case ZA = 'South Africa';
    case ZM = 'Zambia';
    case ZW = 'Zimbabwe';

    /**
     * Returns a list of all country codes.
     */
    public static function codes(): array
    {
        return self::names();
    }

    /**
     * Returns a list of all country names.
     */
    public static function names(): array
    {
        return self::values();
    }
}
