<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

/**
 * Currency codes: https://github.com/sokil/php-isocodes-db-i18n
 * Currency symbols: https://github.com/OpenExchangeAPI/ISO4217/blob/main/data/symbols.json
 */
final class CurrencyUtility
{
    private const CURRENCY_DATA = [
        'AED' => [
            'code' => 'AED',
            'name' => 'UAE Dirham',
            'numeric' => '784',
            'symbol' => 'د.إ',
        ],
        'AFN' => [
            'code' => 'AFN',
            'name' => 'Afghani',
            'numeric' => '971',
            'symbol' => '؋',
        ],
        'ALL' => [
            'code' => 'ALL',
            'name' => 'Lek',
            'numeric' => '008',
            'symbol' => 'L',
        ],
        'AMD' => [
            'code' => 'AMD',
            'name' => 'Armenian Dram',
            'numeric' => '051',
            'symbol' => '֏',
        ],
        'ANG' => [
            'code' => 'ANG',
            'name' => 'Netherlands Antillean Guilder',
            'numeric' => '532',
            'symbol' => '',
        ],
        'AOA' => [
            'code' => 'AOA',
            'name' => 'Kwanza',
            'numeric' => '973',
            'symbol' => 'Kz',
        ],
        'ARS' => [
            'code' => 'ARS',
            'name' => 'Argentine Peso',
            'numeric' => '032',
            'symbol' => '$',
        ],
        'AUD' => [
            'code' => 'AUD',
            'name' => 'Australian Dollar',
            'numeric' => '036',
            'symbol' => '$',
        ],
        'AWG' => [
            'code' => 'AWG',
            'name' => 'Aruban Florin',
            'numeric' => '533',
            'symbol' => 'ƒ',
        ],
        'AZN' => [
            'code' => 'AZN',
            'name' => 'Azerbaijan Manat',
            'numeric' => '944',
            'symbol' => '₼',
        ],
        'BAM' => [
            'code' => 'BAM',
            'name' => 'Convertible Mark',
            'numeric' => '977',
            'symbol' => 'KM',
        ],
        'BBD' => [
            'code' => 'BBD',
            'name' => 'Barbados Dollar',
            'numeric' => '052',
            'symbol' => '$',
        ],
        'BDT' => [
            'code' => 'BDT',
            'name' => 'Taka',
            'numeric' => '050',
            'symbol' => '৳',
        ],
        'BGN' => [
            'code' => 'BGN',
            'name' => 'Bulgarian Lev',
            'numeric' => '975',
            'symbol' => 'лв',
        ],
        'BHD' => [
            'code' => 'BHD',
            'name' => 'Bahraini Dinar',
            'numeric' => '048',
            'symbol' => '.د.ب',
        ],
        'BIF' => [
            'code' => 'BIF',
            'name' => 'Burundi Franc',
            'numeric' => '108',
            'symbol' => 'FBu',
        ],
        'BMD' => [
            'code' => 'BMD',
            'name' => 'Bermudian Dollar',
            'numeric' => '060',
            'symbol' => '$',
        ],
        'BND' => [
            'code' => 'BND',
            'name' => 'Brunei Dollar',
            'numeric' => '096',
            'symbol' => '$',
        ],
        'BOB' => [
            'code' => 'BOB',
            'name' => 'Boliviano',
            'numeric' => '068',
            'symbol' => 'Bs.',
        ],
        'BOV' => [
            'code' => 'BOV',
            'name' => 'Mvdol',
            'numeric' => '984',
            'symbol' => '',
        ],
        'BRL' => [
            'code' => 'BRL',
            'name' => 'Brazilian Real',
            'numeric' => '986',
            'symbol' => 'R$',
        ],
        'BSD' => [
            'code' => 'BSD',
            'name' => 'Bahamian Dollar',
            'numeric' => '044',
            'symbol' => '$',
        ],
        'BTN' => [
            'code' => 'BTN',
            'name' => 'Ngultrum',
            'numeric' => '064',
            'symbol' => 'Nu.',
        ],
        'BWP' => [
            'code' => 'BWP',
            'name' => 'Pula',
            'numeric' => '072',
            'symbol' => 'P',
        ],
        'BYN' => [
            'code' => 'BYN',
            'name' => 'Belarusian Ruble',
            'numeric' => '933',
            'symbol' => 'Br',
        ],
        'BZD' => [
            'code' => 'BZD',
            'name' => 'Belize Dollar',
            'numeric' => '084',
            'symbol' => '$',
        ],
        'CAD' => [
            'code' => 'CAD',
            'name' => 'Canadian Dollar',
            'numeric' => '124',
            'symbol' => '$',
        ],
        'CDF' => [
            'code' => 'CDF',
            'name' => 'Congolese Franc',
            'numeric' => '976',
            'symbol' => 'FC',
        ],
        'CHE' => [
            'code' => 'CHE',
            'name' => 'WIR Euro',
            'numeric' => '947',
            'symbol' => '',
        ],
        'CHF' => [
            'code' => 'CHF',
            'name' => 'Swiss Franc',
            'numeric' => '756',
            'symbol' => 'Fr.',
        ],
        'CHW' => [
            'code' => 'CHW',
            'name' => 'WIR Franc',
            'numeric' => '948',
            'symbol' => '',
        ],
        'CLF' => [
            'code' => 'CLF',
            'name' => 'Unidad de Fomento',
            'numeric' => '990',
            'symbol' => '',
        ],
        'CLP' => [
            'code' => 'CLP',
            'name' => 'Chilean Peso',
            'numeric' => '152',
            'symbol' => '$',
        ],
        'CNY' => [
            'code' => 'CNY',
            'name' => 'Yuan Renminbi',
            'numeric' => '156',
            'symbol' => '¥',
        ],
        'COP' => [
            'code' => 'COP',
            'name' => 'Colombian Peso',
            'numeric' => '170',
            'symbol' => '$',
        ],
        'COU' => [
            'code' => 'COU',
            'name' => 'Unidad de Valor Real',
            'numeric' => '970',
            'symbol' => '',
        ],
        'CRC' => [
            'code' => 'CRC',
            'name' => 'Costa Rican Colon',
            'numeric' => '188',
            'symbol' => '₡',
        ],
        'CUC' => [
            'code' => 'CUC',
            'name' => 'Peso Convertible',
            'numeric' => '931',
            'symbol' => '',
        ],
        'CUP' => [
            'code' => 'CUP',
            'name' => 'Cuban Peso',
            'numeric' => '192',
            'symbol' => '$',
        ],
        'CVE' => [
            'code' => 'CVE',
            'name' => 'Cabo Verde Escudo',
            'numeric' => '132',
            'symbol' => '$',
        ],
        'CZK' => [
            'code' => 'CZK',
            'name' => 'Czech Koruna',
            'numeric' => '203',
            'symbol' => 'Kč',
        ],
        'DJF' => [
            'code' => 'DJF',
            'name' => 'Djibouti Franc',
            'numeric' => '262',
            'symbol' => 'Fdj',
        ],
        'DKK' => [
            'code' => 'DKK',
            'name' => 'Danish Krone',
            'numeric' => '208',
            'symbol' => 'kr',
        ],
        'DOP' => [
            'code' => 'DOP',
            'name' => 'Dominican Peso',
            'numeric' => '214',
            'symbol' => '$',
        ],
        'DZD' => [
            'code' => 'DZD',
            'name' => 'Algerian Dinar',
            'numeric' => '012',
            'symbol' => 'دج',
        ],
        'EGP' => [
            'code' => 'EGP',
            'name' => 'Egyptian Pound',
            'numeric' => '818',
            'symbol' => 'ج.م',
        ],
        'ERN' => [
            'code' => 'ERN',
            'name' => 'Nakfa',
            'numeric' => '232',
            'symbol' => 'Nfk',
        ],
        'ETB' => [
            'code' => 'ETB',
            'name' => 'Ethiopian Birr',
            'numeric' => '230',
            'symbol' => 'Br',
        ],
        'EUR' => [
            'code' => 'EUR',
            'name' => 'Euro',
            'numeric' => '978',
            'symbol' => '€',
        ],
        'FJD' => [
            'code' => 'FJD',
            'name' => 'Fiji Dollar',
            'numeric' => '242',
            'symbol' => '$',
        ],
        'FKP' => [
            'code' => 'FKP',
            'name' => 'Falkland Islands Pound',
            'numeric' => '238',
            'symbol' => '£',
        ],
        'GBP' => [
            'code' => 'GBP',
            'name' => 'Pound Sterling',
            'numeric' => '826',
            'symbol' => '£',
        ],
        'GEL' => [
            'code' => 'GEL',
            'name' => 'Lari',
            'numeric' => '981',
            'symbol' => '₾',
        ],
        'GHS' => [
            'code' => 'GHS',
            'name' => 'Ghana Cedi',
            'numeric' => '936',
            'symbol' => '₵',
        ],
        'GIP' => [
            'code' => 'GIP',
            'name' => 'Gibraltar Pound',
            'numeric' => '292',
            'symbol' => '£',
        ],
        'GMD' => [
            'code' => 'GMD',
            'name' => 'Dalasi',
            'numeric' => '270',
            'symbol' => 'D',
        ],
        'GNF' => [
            'code' => 'GNF',
            'name' => 'Guinean Franc',
            'numeric' => '324',
            'symbol' => 'FG',
        ],
        'GTQ' => [
            'code' => 'GTQ',
            'name' => 'Quetzal',
            'numeric' => '320',
            'symbol' => 'Q',
        ],
        'GYD' => [
            'code' => 'GYD',
            'name' => 'Guyana Dollar',
            'numeric' => '328',
            'symbol' => '$',
        ],
        'HKD' => [
            'code' => 'HKD',
            'name' => 'Hong Kong Dollar',
            'numeric' => '344',
            'symbol' => '$',
        ],
        'HNL' => [
            'code' => 'HNL',
            'name' => 'Lempira',
            'numeric' => '340',
            'symbol' => 'L',
        ],
        'HRK' => [
            'code' => 'HRK',
            'name' => 'Kuna',
            'numeric' => '191',
            'symbol' => '',
        ],
        'HTG' => [
            'code' => 'HTG',
            'name' => 'Gourde',
            'numeric' => '332',
            'symbol' => 'G',
        ],
        'HUF' => [
            'code' => 'HUF',
            'name' => 'Forint',
            'numeric' => '348',
            'symbol' => 'Ft',
        ],
        'IDR' => [
            'code' => 'IDR',
            'name' => 'Rupiah',
            'numeric' => '360',
            'symbol' => 'Rp',
        ],
        'ILS' => [
            'code' => 'ILS',
            'name' => 'New Israeli Sheqel',
            'numeric' => '376',
            'symbol' => '₪',
        ],
        'INR' => [
            'code' => 'INR',
            'name' => 'Indian Rupee',
            'numeric' => '356',
            'symbol' => '₹',
        ],
        'IQD' => [
            'code' => 'IQD',
            'name' => 'Iraqi Dinar',
            'numeric' => '368',
            'symbol' => 'ع.د',
        ],
        'IRR' => [
            'code' => 'IRR',
            'name' => 'Iranian Rial',
            'numeric' => '364',
            'symbol' => '﷼',
        ],
        'ISK' => [
            'code' => 'ISK',
            'name' => 'Iceland Krona',
            'numeric' => '352',
            'symbol' => 'kr',
        ],
        'JMD' => [
            'code' => 'JMD',
            'name' => 'Jamaican Dollar',
            'numeric' => '388',
            'symbol' => '$',
        ],
        'JOD' => [
            'code' => 'JOD',
            'name' => 'Jordanian Dinar',
            'numeric' => '400',
            'symbol' => 'د.ا',
        ],
        'JPY' => [
            'code' => 'JPY',
            'name' => 'Yen',
            'numeric' => '392',
            'symbol' => '¥',
        ],
        'KES' => [
            'code' => 'KES',
            'name' => 'Kenyan Shilling',
            'numeric' => '404',
            'symbol' => 'KSh',
        ],
        'KGS' => [
            'code' => 'KGS',
            'name' => 'Som',
            'numeric' => '417',
            'symbol' => 'лв',
        ],
        'KHR' => [
            'code' => 'KHR',
            'name' => 'Riel',
            'numeric' => '116',
            'symbol' => '៛',
        ],
        'KMF' => [
            'code' => 'KMF',
            'name' => 'Comorian Franc',
            'numeric' => '174',
            'symbol' => 'CF',
        ],
        'KPW' => [
            'code' => 'KPW',
            'name' => 'North Korean Won',
            'numeric' => '408',
            'symbol' => '₩',
        ],
        'KRW' => [
            'code' => 'KRW',
            'name' => 'Won',
            'numeric' => '410',
            'symbol' => '₩',
        ],
        'KWD' => [
            'code' => 'KWD',
            'name' => 'Kuwaiti Dinar',
            'numeric' => '414',
            'symbol' => 'د.ك',
        ],
        'KYD' => [
            'code' => 'KYD',
            'name' => 'Cayman Islands Dollar',
            'numeric' => '136',
            'symbol' => '$',
        ],
        'KZT' => [
            'code' => 'KZT',
            'name' => 'Tenge',
            'numeric' => '398',
            'symbol' => '₸',
        ],
        'LAK' => [
            'code' => 'LAK',
            'name' => 'Lao Kip',
            'numeric' => '418',
            'symbol' => '₭',
        ],
        'LBP' => [
            'code' => 'LBP',
            'name' => 'Lebanese Pound',
            'numeric' => '422',
            'symbol' => 'ل.ل',
        ],
        'LKR' => [
            'code' => 'LKR',
            'name' => 'Sri Lanka Rupee',
            'numeric' => '144',
            'symbol' => '₨',
        ],
        'LRD' => [
            'code' => 'LRD',
            'name' => 'Liberian Dollar',
            'numeric' => '430',
            'symbol' => '$',
        ],
        'LSL' => [
            'code' => 'LSL',
            'name' => 'Loti',
            'numeric' => '426',
            'symbol' => 'L',
        ],
        'LYD' => [
            'code' => 'LYD',
            'name' => 'Libyan Dinar',
            'numeric' => '434',
            'symbol' => 'ل.د',
        ],
        'MAD' => [
            'code' => 'MAD',
            'name' => 'Moroccan Dirham',
            'numeric' => '504',
            'symbol' => 'د.م.',
        ],
        'MDL' => [
            'code' => 'MDL',
            'name' => 'Moldovan Leu',
            'numeric' => '498',
            'symbol' => 'L',
        ],
        'MGA' => [
            'code' => 'MGA',
            'name' => 'Malagasy Ariary',
            'numeric' => '969',
            'symbol' => 'Ar',
        ],
        'MKD' => [
            'code' => 'MKD',
            'name' => 'Denar',
            'numeric' => '807',
            'symbol' => 'ден',
        ],
        'MMK' => [
            'code' => 'MMK',
            'name' => 'Kyat',
            'numeric' => '104',
            'symbol' => 'K',
        ],
        'MNT' => [
            'code' => 'MNT',
            'name' => 'Tugrik',
            'numeric' => '496',
            'symbol' => '₮',
        ],
        'MOP' => [
            'code' => 'MOP',
            'name' => 'Pataca',
            'numeric' => '446',
            'symbol' => 'P',
        ],
        'MRU' => [
            'code' => 'MRU',
            'name' => 'Ouguiya',
            'numeric' => '929',
            'symbol' => 'UM',
        ],
        'MUR' => [
            'code' => 'MUR',
            'name' => 'Mauritius Rupee',
            'numeric' => '480',
            'symbol' => '₨',
        ],
        'MVR' => [
            'code' => 'MVR',
            'name' => 'Rufiyaa',
            'numeric' => '462',
            'symbol' => 'Rf',
        ],
        'MWK' => [
            'code' => 'MWK',
            'name' => 'Malawi Kwacha',
            'numeric' => '454',
            'symbol' => 'MK',
        ],
        'MXN' => [
            'code' => 'MXN',
            'name' => 'Mexican Peso',
            'numeric' => '484',
            'symbol' => '$',
        ],
        'MXV' => [
            'code' => 'MXV',
            'name' => 'Mexican Unidad de Inversion (UDI)',
            'numeric' => '979',
            'symbol' => '',
        ],
        'MYR' => [
            'code' => 'MYR',
            'name' => 'Malaysian Ringgit',
            'numeric' => '458',
            'symbol' => 'RM',
        ],
        'MZN' => [
            'code' => 'MZN',
            'name' => 'Mozambique Metical',
            'numeric' => '943',
            'symbol' => 'MT',
        ],
        'NAD' => [
            'code' => 'NAD',
            'name' => 'Namibia Dollar',
            'numeric' => '516',
            'symbol' => '$',
        ],
        'NGN' => [
            'code' => 'NGN',
            'name' => 'Naira',
            'numeric' => '566',
            'symbol' => '₦',
        ],
        'NIO' => [
            'code' => 'NIO',
            'name' => 'Cordoba Oro',
            'numeric' => '558',
            'symbol' => 'C$',
        ],
        'NOK' => [
            'code' => 'NOK',
            'name' => 'Norwegian Krone',
            'numeric' => '578',
            'symbol' => 'kr',
        ],
        'NPR' => [
            'code' => 'NPR',
            'name' => 'Nepalese Rupee',
            'numeric' => '524',
            'symbol' => '₨',
        ],
        'NZD' => [
            'code' => 'NZD',
            'name' => 'New Zealand Dollar',
            'numeric' => '554',
            'symbol' => '$',
        ],
        'OMR' => [
            'code' => 'OMR',
            'name' => 'Rial Omani',
            'numeric' => '512',
            'symbol' => 'ر.ع.',
        ],
        'PAB' => [
            'code' => 'PAB',
            'name' => 'Balboa',
            'numeric' => '590',
            'symbol' => 'B/.',
        ],
        'PEN' => [
            'code' => 'PEN',
            'name' => 'Sol',
            'numeric' => '604',
            'symbol' => 'S/.',
        ],
        'PGK' => [
            'code' => 'PGK',
            'name' => 'Kina',
            'numeric' => '598',
            'symbol' => 'K',
        ],
        'PHP' => [
            'code' => 'PHP',
            'name' => 'Philippine Peso',
            'numeric' => '608',
            'symbol' => '₱',
        ],
        'PKR' => [
            'code' => 'PKR',
            'name' => 'Pakistan Rupee',
            'numeric' => '586',
            'symbol' => '₨',
        ],
        'PLN' => [
            'code' => 'PLN',
            'name' => 'Zloty',
            'numeric' => '985',
            'symbol' => 'zł',
        ],
        'PYG' => [
            'code' => 'PYG',
            'name' => 'Guarani',
            'numeric' => '600',
            'symbol' => 'Gs',
        ],
        'QAR' => [
            'code' => 'QAR',
            'name' => 'Qatari Rial',
            'numeric' => '634',
            'symbol' => 'ر.ق',
        ],
        'RON' => [
            'code' => 'RON',
            'name' => 'Romanian Leu',
            'numeric' => '946',
            'symbol' => 'lei',
        ],
        'RSD' => [
            'code' => 'RSD',
            'name' => 'Serbian Dinar',
            'numeric' => '941',
            'symbol' => 'дин.',
        ],
        'RUB' => [
            'code' => 'RUB',
            'name' => 'Russian Ruble',
            'numeric' => '643',
            'symbol' => '₽',
        ],
        'RWF' => [
            'code' => 'RWF',
            'name' => 'Rwanda Franc',
            'numeric' => '646',
            'symbol' => 'FRw',
        ],
        'SAR' => [
            'code' => 'SAR',
            'name' => 'Saudi Riyal',
            'numeric' => '682',
            'symbol' => 'ر.س',
        ],
        'SBD' => [
            'code' => 'SBD',
            'name' => 'Solomon Islands Dollar',
            'numeric' => '090',
            'symbol' => '$',
        ],
        'SCR' => [
            'code' => 'SCR',
            'name' => 'Seychelles Rupee',
            'numeric' => '690',
            'symbol' => '₨',
        ],
        'SDG' => [
            'code' => 'SDG',
            'name' => 'Sudanese Pound',
            'numeric' => '938',
            'symbol' => 'ج.س.',
        ],
        'SEK' => [
            'code' => 'SEK',
            'name' => 'Swedish Krona',
            'numeric' => '752',
            'symbol' => 'kr',
        ],
        'SGD' => [
            'code' => 'SGD',
            'name' => 'Singapore Dollar',
            'numeric' => '702',
            'symbol' => '$',
        ],
        'SHP' => [
            'code' => 'SHP',
            'name' => 'Saint Helena Pound',
            'numeric' => '654',
            'symbol' => '£',
        ],
        'SLE' => [
            'code' => 'SLE',
            'name' => 'Leone',
            'numeric' => '925',
            'symbol' => 'Le',
        ],
        'SLL' => [
            'code' => 'SLL',
            'name' => 'Leone',
            'numeric' => '694',
            'symbol' => '',
        ],
        'SOS' => [
            'code' => 'SOS',
            'name' => 'Somali Shilling',
            'numeric' => '706',
            'symbol' => 'Sh',
        ],
        'SRD' => [
            'code' => 'SRD',
            'name' => 'Surinam Dollar',
            'numeric' => '968',
            'symbol' => '$',
        ],
        'SSP' => [
            'code' => 'SSP',
            'name' => 'South Sudanese Pound',
            'numeric' => '728',
            'symbol' => '£',
        ],
        'STN' => [
            'code' => 'STN',
            'name' => 'Dobra',
            'numeric' => '930',
            'symbol' => 'Db',
        ],
        'SVC' => [
            'code' => 'SVC',
            'name' => 'El Salvador Colon',
            'numeric' => '222',
            'symbol' => '$',
        ],
        'SYP' => [
            'code' => 'SYP',
            'name' => 'Syrian Pound',
            'numeric' => '760',
            'symbol' => '£',
        ],
        'SZL' => [
            'code' => 'SZL',
            'name' => 'Lilangeni',
            'numeric' => '748',
            'symbol' => 'E',
        ],
        'THB' => [
            'code' => 'THB',
            'name' => 'Baht',
            'numeric' => '764',
            'symbol' => '฿',
        ],
        'TJS' => [
            'code' => 'TJS',
            'name' => 'Somoni',
            'numeric' => '972',
            'symbol' => 'ЅM',
        ],
        'TMT' => [
            'code' => 'TMT',
            'name' => 'Turkmenistan New Manat',
            'numeric' => '934',
            'symbol' => 'm',
        ],
        'TND' => [
            'code' => 'TND',
            'name' => 'Tunisian Dinar',
            'numeric' => '788',
            'symbol' => 'د.ت',
        ],
        'TOP' => [
            'code' => 'TOP',
            'name' => 'Pa’anga',
            'numeric' => '776',
            'symbol' => 'T$',
        ],
        'TRY' => [
            'code' => 'TRY',
            'name' => 'Turkish Lira',
            'numeric' => '949',
            'symbol' => '₺',
        ],
        'TTD' => [
            'code' => 'TTD',
            'name' => 'Trinidad and Tobago Dollar',
            'numeric' => '780',
            'symbol' => '$',
        ],
        'TWD' => [
            'code' => 'TWD',
            'name' => 'New Taiwan Dollar',
            'numeric' => '901',
            'symbol' => '$',
        ],
        'TZS' => [
            'code' => 'TZS',
            'name' => 'Tanzanian Shilling',
            'numeric' => '834',
            'symbol' => 'Sh',
        ],
        'UAH' => [
            'code' => 'UAH',
            'name' => 'Hryvnia',
            'numeric' => '980',
            'symbol' => '₴',
        ],
        'UGX' => [
            'code' => 'UGX',
            'name' => 'Uganda Shilling',
            'numeric' => '800',
            'symbol' => 'USh',
        ],
        'USD' => [
            'code' => 'USD',
            'name' => 'US Dollar',
            'numeric' => '840',
            'symbol' => '$',
        ],
        'USN' => [
            'code' => 'USN',
            'name' => 'US Dollar (Next day)',
            'numeric' => '997',
            'symbol' => '$',
        ],
        'UYI' => [
            'code' => 'UYI',
            'name' => 'Uruguay Peso en Unidades Indexadas (UI)',
            'numeric' => '940',
            'symbol' => '',
        ],
        'UYU' => [
            'code' => 'UYU',
            'name' => 'Peso Uruguayo',
            'numeric' => '858',
            'symbol' => '$U',
        ],
        'UYW' => [
            'code' => 'UYW',
            'name' => 'Unidad Previsional',
            'numeric' => '927',
            'symbol' => '',
        ],
        'UZS' => [
            'code' => 'UZS',
            'name' => 'Uzbekistan Sum',
            'numeric' => '860',
            'symbol' => 'so\'m',
        ],
        'VED' => [
            'code' => 'VED',
            'name' => 'Bolívar Soberano',
            'numeric' => '926',
            'symbol' => '',
        ],
        'VES' => [
            'code' => 'VES',
            'name' => 'Bolívar Soberano',
            'numeric' => '928',
            'symbol' => 'Bs.S',
        ],
        'VND' => [
            'code' => 'VND',
            'name' => 'Dong',
            'numeric' => '704',
            'symbol' => '₫',
        ],
        'VUV' => [
            'code' => 'VUV',
            'name' => 'Vatu',
            'numeric' => '548',
            'symbol' => 'Vt',
        ],
        'WST' => [
            'code' => 'WST',
            'name' => 'Tala',
            'numeric' => '882',
            'symbol' => 'WS$',
        ],
        'XAF' => [
            'code' => 'XAF',
            'name' => 'CFA Franc BEAC',
            'numeric' => '950',
            'symbol' => 'FCFA',
        ],
        'XAG' => [
            'code' => 'XAG',
            'name' => 'Silver',
            'numeric' => '961',
            'symbol' => '',
        ],
        'XAU' => [
            'code' => 'XAU',
            'name' => 'Gold',
            'numeric' => '959',
            'symbol' => '',
        ],
        'XBA' => [
            'code' => 'XBA',
            'name' => 'Bond Markets Unit European Composite Unit (EURCO)',
            'numeric' => '955',
            'symbol' => '',
        ],
        'XBB' => [
            'code' => 'XBB',
            'name' => 'Bond Markets Unit European Monetary Unit (E.M.U.-6)',
            'numeric' => '956',
            'symbol' => '',
        ],
        'XBC' => [
            'code' => 'XBC',
            'name' => 'Bond Markets Unit European Unit of Account 9 (E.U.A.-9)',
            'numeric' => '957',
            'symbol' => '',
        ],
        'XBD' => [
            'code' => 'XBD',
            'name' => 'Bond Markets Unit European Unit of Account 17 (E.U.A.-17)',
            'numeric' => '958',
            'symbol' => '',
        ],
        'XCD' => [
            'code' => 'XCD',
            'name' => 'East Caribbean Dollar',
            'numeric' => '951',
            'symbol' => '$',
        ],
        'XDR' => [
            'code' => 'XDR',
            'name' => 'SDR (Special Drawing Right)',
            'numeric' => '960',
            'symbol' => '',
        ],
        'XOF' => [
            'code' => 'XOF',
            'name' => 'CFA Franc BCEAO',
            'numeric' => '952',
            'symbol' => 'CFA',
        ],
        'XPD' => [
            'code' => 'XPD',
            'name' => 'Palladium',
            'numeric' => '964',
            'symbol' => '',
        ],
        'XPF' => [
            'code' => 'XPF',
            'name' => 'CFP Franc',
            'numeric' => '953',
            'symbol' => '₣',
        ],
        'XPT' => [
            'code' => 'XPT',
            'name' => 'Platinum',
            'numeric' => '962',
            'symbol' => '',
        ],
        'XSU' => [
            'code' => 'XSU',
            'name' => 'Sucre',
            'numeric' => '994',
            'symbol' => '',
        ],
        'XTS' => [
            'code' => 'XTS',
            'name' => 'Codes specifically reserved for testing purposes',
            'numeric' => '963',
            'symbol' => '',
        ],
        'XUA' => [
            'code' => 'XUA',
            'name' => 'ADB Unit of Account',
            'numeric' => '965',
            'symbol' => '',
        ],
        'XXX' => [
            'code' => 'XXX',
            'name' => 'The codes assigned for transactions where no currency is involved',
            'numeric' => '999',
            'symbol' => '',
        ],
        'YER' => [
            'code' => 'YER',
            'name' => 'Yemeni Rial',
            'numeric' => '886',
            'symbol' => '﷼',
        ],
        'ZAR' => [
            'code' => 'ZAR',
            'name' => 'Rand',
            'numeric' => '710',
            'symbol' => 'R',
        ],
        'ZMW' => [
            'code' => 'ZMW',
            'name' => 'Zambian Kwacha',
            'numeric' => '967',
            'symbol' => 'ZK',
        ],
        'ZWL' => [
            'code' => 'ZWL',
            'name' => 'Zimbabwe Dollar',
            'numeric' => '932',
            'symbol' => '',
        ],
    ];

    public static function getByIsoCode(string $isoCode): ?array
    {
        $isoCode = strtoupper($isoCode);
        if (isset(self::CURRENCY_DATA[$isoCode])) {
            return self::CURRENCY_DATA[$isoCode];
        }
        return null;
    }

    public static function getBySymbol(string $symbol): ?array
    {
        foreach (self::CURRENCY_DATA as $currency) {
            if ($currency['symbol'] === $symbol) {
                return $currency;
            }
        }
        return null;
    }

    public static function getAllIsoCodes(): array
    {
        return array_keys(self::CURRENCY_DATA);
    }
}
