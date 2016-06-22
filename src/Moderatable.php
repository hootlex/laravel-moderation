<?php

namespace Hootlex\Moderation;



trait Moderatable
{
    use ModerationQueryBuilder;

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootModeratable()
    {
        static::addGlobalScope(new ModerationScope);
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
     * Change resource status to Postpone
     *
     * @param null $id
     *
     * @return mixed
     */
    public static function postpone($id)
    {
        return (new static)->newQueryWithoutScope(new ModerationScope())->postpone($id);
    }

    /**
     * Change Instance's status to Approved
     *
     * @return mixed
     */
    public function markApproved()
    {
        $new = (new static)->newQueryWithoutScope(new ModerationScope())->approve($this->id);
        return $this->setRawAttributes($new->attributesToArray());
    }

    /**
     * Change Instance's status to Rejected
     *
     * @return mixed
     */
    public function markRejected()
    {
        $new = (new static)->newQueryWithoutScope(new ModerationScope())->reject($this->id);
        return $this->setRawAttributes($new->attributesToArray());
    }

    /**
     * Change Instance's status to Postponed
     *
     * @return mixed
     */
    public function markPostponed()
    {
        $new = (new static)->newQueryWithoutScope(new ModerationScope())->postpone($this->id);
        return $this->setRawAttributes($new->attributesToArray());
    }

    /**
     * Change Instance's status to Pending
     *
     * @return mixed
     */
    public function markPending()
    {
        $new = (new static)->newQueryWithoutScope(new ModerationScope())->pend($this->id);
        return $this->setRawAttributes($new->attributesToArray());
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
     * Determine if the model instance has been postponed.
     *
     * @return bool
     */
    public function isPostponed()
    {
        return $this->{$this->getStatusColumn()} == Status::POSTPONED;
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
     * Get the fully qualified "moderated by" column.
     *
     * @return string
     */
    public function getQualifiedModeratedByColumn()
    {
        return $this->getTable() . '.' . $this->getModeratedByColumn();
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
     * Get the name of the "moderated by" column.
     *
     * @return string
     */
    public function getModeratedByColumn()
    {
        return defined('static::MODERATED_BY') ? static::MODERATED_BY : config('moderation.moderated_by_column');
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