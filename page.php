<?php
// ref: http://www.itworkman.com/224967.html
// ref: https://topic.alibabacloud.com/a/php-page-display-method-analysis-with-php-universal-paging-class_4_86_30931519.html
class Page
{
    protected $count; // Total count
    protected $showPages; // Number of pages to be displayed
    protected $countPages; // Total page
    protected $currPage; // Current page
    protected $subPages; // Number of records per page
    protected $href; //link
    protected $page_arr = array(); // Save the generated page number Key page number Value is connected

    /**
     * __construct constructor (to get the required parameters for paging)
     * @param int $count Total count
     * @param int $showPages Number of pages to be displayed
     * @param int $currPage Current page
     * @param int $subPages Number of records per page
     * @param string $href Connection (get current URL if not set)
     */
    public function __construct()
    {
        $this->showPages = 5;
        $this->currPage = isset($_GET['page']) ? $_GET['page'] : 1;;
        $this->subPages = 7;

// Get the current connection if the link is not set
        if (empty($href)) {
            $this->href = htmlentities($_SERVER['PHP_SELF']);
        } else {
            $this->href = $href;
        }
    }


    public function setCount($query)
    {
        $query = mysql_fetch_assoc($query);
        // echo $query['count(*)'];
        $this->count = $query['count(*)'];
    }

    /**
     * getPages Returns an array of page numbers
     * @return array One-dimensional array, key is the page number, value is the link
     */
    public function getPages()
    {
        return $this->page_arr;
    }

    public function limit()
    {
        return "LIMIT " . ($this->currPage - 1) * $this->subPages . ' ,' . $this->subPages ;
    }

    /**
     * showPages Return the generated page number
     * @param int $style style
     * @return string Generate page numbers
     */
    public function showPages($style = 1)
    {
        $this->construct_Pages();
        $func = 'pageStyle' . $style;
        return $this->$func();
    }

    /**
     * pageStyle1 Page break style (you can refer to this to add custom styles such as pageStyle2 ())
     * Style Total 45 records,Show 10 records per page,Current page 1/4 [Home] [Previous] [1] [2] [3] ... [next] [last]
     * @return string
     */
    protected function pageStyle1()
    {

        /* Constructing pagination in normal mode
        Total 4523 records,10 records per page,current page 1/453 [Home] [Previous] [1] [2] [3] ... [Next] [Last]
        */
        $pageStr = '<div  class="table-wrapper" style="margin-top: 30px;
    padding-bottom: 30px;">';
        $pageStr .= 'There are ' . $this->count . ' records, each page will show ' . $this->subPages . ' records. ';
        $pageStr .= 'Now is in ' . $this->currPage . '/' . $this->countPages . ' page ';

        $_GET['page'] = 1;
        $pageStr .= '<span>[<a href="' . $this->href . '?' . http_build_query($_GET) . '">Frist page</a>] </span>';
// If the current page is not the first page, display the previous page
        if ($this->currPage > 1) {
            $_GET['page'] = $this->currPage - 1;
            $pageStr .= '<span>[<a href="' . $this->href . '?' . http_build_query($_GET) . '">Previous page</a>] </span>';
        }

        foreach ($this->page_arr as $k => $v) {
            $_GET['page'] = $k;
            $pageStr .= '<span>[<a href="' . $v . '">' . $k . '</a>] </span>';
        }

// If the current page is less than the total number of pages, display the next page
        if ($this->currPage < $this->countPages) {
            $_GET['page'] = $this->currPage + 1;
            $pageStr .= '<span>[<a href="' . $this->href . '?' . http_build_query($_GET) . '">Next page</a>] </span>';
        }

        $_GET['page'] = $this->countPages;
        $pageStr .= '<span>[<a href="' . $this->href . '?' . http_build_query($_GET) . '">Last page</a>] </span>';

        return $pageStr . '</div>';
    }


    /**
     * construct_Pages Generate an array of page numbers
     * The key is the page number and the value is the link
     * $this->page_arr=Array(
     * [1] => index.php?page=1
     * [2] => index.php?page=2
     * [3] => index.php?page=3
     * ......)
     */
    protected function construct_Pages()
    {
// Calculate the total number of pages
        $this->countPages = ceil($this->count / $this->subPages);
// Calculate the number of pages before and after based on the current page
        $leftPage_num = floor($this->showPages / 2);
        $rightPage_num = $this->showPages - $leftPage_num;

// The number displayed on the left is the current page minus the number displayed on the left.
// For example, the total number of pages displayed is 7, 
// the current page is 5, and the minimum on the left is 5-3, and the minimum on the right is 5+3.
        $left = $this->currPage - $leftPage_num;
        $left = max($left, 1); // The minimum left side cannot be less than 1
        $right = $left + $this->showPages - 1; // Left plus the number of pages displayed minus 1 is the number displayed on the right
        $right = min($right, $this->countPages); // The maximum right-hand side cannot be larger than the total number of pages
        $left = max($right - $this->showPages + 1, 1); // Determine the right side and then calculate the left side, which must be calculated twice

        for ($i = $left; $i <= $right; $i++) {
            $_GET['page'] = $i;
            $this->page_arr[$i] = $this->href . '?' . http_build_query($_GET);
        }
    }
}
