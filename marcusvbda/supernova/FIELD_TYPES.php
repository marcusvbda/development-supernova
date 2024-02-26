<?php

namespace marcusvbda\supernova;

enum FIELD_TYPES: string
{
    case COLOR = "color";
    case URL = "url";
    case TEXT = "text";
    case NUMBER = "number";
    case SELECT = "select";
    case RESOURCE = "resource";
}
