type CookieDurationPeriods = 'session' | 'minutes' | 'hours' | 'days' | 'months';

type CookieDuration = {
    period: CookieDurationPeriods,
    value: number,
}