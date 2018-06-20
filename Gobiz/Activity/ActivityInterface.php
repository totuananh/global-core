<?php

namespace Gobiz\Activity;

interface ActivityInterface
{
    /**
     * Lấy log id
     *
     * @return string|null
     */
    public function getId();

    /**
     * Lấy thông tin người thực hiện
     *
     * @return ActivityCreatorInterface
     */
    public function getCreator();

    /**
     * Lấy hành động thực hiện
     *
     * @return string
     */
    public function getAction();

    /**
     * Log co public cho khach hang xem ko
     *
     * @return boolean
     */
    public function getIsPublic();

    /**
     * get is message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Lấy danh sách các đối tượng bị ảnh hưởng bởi hành động
     *
     * @return array
     */
    public function getObjects();

    /**
     * Lấy thời gian thực hiện
     *
     * @return int
     */
    public function getTime();

    /**
     * Lấy dữ liệu bổ sung
     *
     * @return array
     */
    public function getPayload();

    /**
     * Lấy dữ liệu activity dạng array
     *
     * @return array
     */
    public function getActivityAsArray();
}