<?php

namespace Hootlex\Moderation;



trait ModerationQueryBuilder
{
    /**
     * Get a new query builder that only includes pending resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function pending()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->pending();
    }

    /**
     * Get a new query builder that only includes rejected resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function rejected()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->rejected();
    }

    /**
     * Get a new query builder that only includes postponed resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function postponed()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->postponed();
    }

    /**
     * Get a new query builder that includes pending resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withPending()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->withPending();
    }

    /**
     * Get a new query builder that includes rejected resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withRejected()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->withRejected();
    }

    /**
     * Get a new query builder that includes postponed resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withPostponed()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->withPostponed();
    }

    /**
     * Get a new query builder that includes all resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withAnyStatus()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope());
    }
}