<?php

namespace Gobiz\Activity;

interface ActivityCreatorInterface
{
    /**
     * Get the creator type
     *
     * @return string
     */
    public function getType();

    /**
     * Get the creator id
     *
     * @return int
     */
    public function getId();

    /**
     * Get the creator username
     *
     * @return string
     */
    public function getUsername();

    /**
     * Get the creator name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the creator partner_id
     *
     * @return int
     */
    public function getPartnerId();

    /**
     * Get the creator is_admin
     *
     * @return int
     */
    public function getIsAdmin();
}