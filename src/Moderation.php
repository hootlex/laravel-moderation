<?php

namespace Hootlex\Moderation;



trait Moderation
{
    /**
     * Indicates if the model is currently force deleting.
     *
     * @var bool
     */
    protected $forceDeleting = false;

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootModeration()
    {
        static::addGlobalScope(new ModerationScope);
    }

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
     * Get a new query builder that includes all resources.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function withAnyStatus()
    {
        return (new static)->newQueryWithoutScope(new ModerationScope());
    }

    /**
     * Change resource status to Approved
     *
     * @param $id
     *
     * @return mixed
     */
    public static function approve($id)
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->approve($id);
    }

    /**
     * Change resource status to Rejected
     *
     * @param null $id
     *
     * @return mixed
     */
    public static function reject($id)
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->reject($id);
    }

    /**
     * Determine if the model instance has been approved.
     *
     * @return bool
     */
    public function isApproved()
    {
        return $this->{$this->getStatusColumn()} == Status::APPROVED;
    }

    /**
     * Determine if the model instance has been approved.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->{$this->getStatusColumn()} == Status::REJECTED;
    }

    /**
     * Determine if the model instance has been approved.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->{$this->getStatusColumn()} == Status::PENDING;
    }

    /**
     * Get the name of the "status" column.
     *
     * @return string
     */
    public function getStatusColumn()
    {
        return defined('static::MODERATION_STATUS') ? static::MODERATION_STATUS : config('moderation.status_column');
    }

    /**
     * Get the fully qualified "status" column.
     *
     * @return string
     */
    public function getQualifiedStatusColumn()
    {
        return $this->getTable() . '.' . $this->getStatusColumn();
    }

    /**
     * Get the fully qualified "moderated at" column.
     *
     * @return string
     */
    public function getQualifiedModeratedAtColumn()
    {
        return $this->getTable() . '.' . $this->getModeratedAtColumn();
    }

    /**
     * Get the name of the "moderated at" column.
     *
     * @return string
     */
    public function getModeratedAtColumn()
    {
        return defined('static::MODERATED_AT') ? static::MODERATED_AT : config('moderation.moderated_at_column');
    }

    /**
     * Get the name of the "moderated at" column.
     * Append "moderated at" column to the attributes that should be converted to dates.
     *
     * @return string
     */
    public function getDates(){
        return array_merge(parent::getDates(), [$this->getModeratedAtColumn()]);
    }

}