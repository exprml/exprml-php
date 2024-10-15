<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# NO CHECKED-IN PROTOBUF GENCODE
# source: exprml/v1/expr.proto

namespace Exprml\PB\Exprml\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Iter is an Iter expression.
 *
 * Generated from protobuf message <code>exprml.v1.Iter</code>
 */
class Iter extends \Google\Protobuf\Internal\Message
{
    /**
     * PosIdent is the identifier of the position.
     *
     * Generated from protobuf field <code>string pos_ident = 1 [json_name = "posIdent"];</code>
     */
    protected $pos_ident = '';
    /**
     * ElemIdent is the identifier of the element.
     *
     * Generated from protobuf field <code>string elem_ident = 2 [json_name = "elemIdent"];</code>
     */
    protected $elem_ident = '';
    /**
     * Col is the collection to iterate.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr col = 3 [json_name = "col"];</code>
     */
    protected $col = null;
    /**
     * Do is the body of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr do = 4 [json_name = "do"];</code>
     */
    protected $do = null;
    /**
     * If is the condition of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr if = 5 [json_name = "if"];</code>
     */
    protected $if = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $pos_ident
     *           PosIdent is the identifier of the position.
     *     @type string $elem_ident
     *           ElemIdent is the identifier of the element.
     *     @type \Exprml\PB\Exprml\V1\Expr $col
     *           Col is the collection to iterate.
     *     @type \Exprml\PB\Exprml\V1\Expr $do
     *           Do is the body of the iteration.
     *     @type \Exprml\PB\Exprml\V1\Expr $if
     *           If is the condition of the iteration.
     * }
     */
    public function __construct($data = NULL) {
        \Exprml\PB\Exprml\V1\GPBMetadata\Expr::initOnce();
        parent::__construct($data);
    }

    /**
     * PosIdent is the identifier of the position.
     *
     * Generated from protobuf field <code>string pos_ident = 1 [json_name = "posIdent"];</code>
     * @return string
     */
    public function getPosIdent()
    {
        return $this->pos_ident;
    }

    /**
     * PosIdent is the identifier of the position.
     *
     * Generated from protobuf field <code>string pos_ident = 1 [json_name = "posIdent"];</code>
     * @param string $var
     * @return $this
     */
    public function setPosIdent($var)
    {
        GPBUtil::checkString($var, True);
        $this->pos_ident = $var;

        return $this;
    }

    /**
     * ElemIdent is the identifier of the element.
     *
     * Generated from protobuf field <code>string elem_ident = 2 [json_name = "elemIdent"];</code>
     * @return string
     */
    public function getElemIdent()
    {
        return $this->elem_ident;
    }

    /**
     * ElemIdent is the identifier of the element.
     *
     * Generated from protobuf field <code>string elem_ident = 2 [json_name = "elemIdent"];</code>
     * @param string $var
     * @return $this
     */
    public function setElemIdent($var)
    {
        GPBUtil::checkString($var, True);
        $this->elem_ident = $var;

        return $this;
    }

    /**
     * Col is the collection to iterate.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr col = 3 [json_name = "col"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getCol()
    {
        return $this->col;
    }

    public function hasCol()
    {
        return isset($this->col);
    }

    public function clearCol()
    {
        unset($this->col);
    }

    /**
     * Col is the collection to iterate.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr col = 3 [json_name = "col"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setCol($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->col = $var;

        return $this;
    }

    /**
     * Do is the body of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr do = 4 [json_name = "do"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getDo()
    {
        return $this->do;
    }

    public function hasDo()
    {
        return isset($this->do);
    }

    public function clearDo()
    {
        unset($this->do);
    }

    /**
     * Do is the body of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr do = 4 [json_name = "do"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setDo($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->do = $var;

        return $this;
    }

    /**
     * If is the condition of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr if = 5 [json_name = "if"];</code>
     * @return \Exprml\PB\Exprml\V1\Expr|null
     */
    public function getIf()
    {
        return $this->if;
    }

    public function hasIf()
    {
        return isset($this->if);
    }

    public function clearIf()
    {
        unset($this->if);
    }

    /**
     * If is the condition of the iteration.
     *
     * Generated from protobuf field <code>.exprml.v1.Expr if = 5 [json_name = "if"];</code>
     * @param \Exprml\PB\Exprml\V1\Expr $var
     * @return $this
     */
    public function setIf($var)
    {
        GPBUtil::checkMessage($var, \Exprml\PB\Exprml\V1\Expr::class);
        $this->if = $var;

        return $this;
    }

}
