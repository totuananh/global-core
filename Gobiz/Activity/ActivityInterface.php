<?php

namespace Gobiz\Activity;

use DateTime;

interface ActivityInterface
{
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
     * Lấy danh sách các đối tượng bị ảnh hưởng bởi hành động
     *
     * @return array
     */
    public function getObjects();

    /**
     * Lấy mô tả cho hành động
     *
     * @return string
     */
    public function getDescription();

    /**
     * Lấy thời gian thực hiện
     *
     * @return DateTime
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