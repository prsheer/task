<?php

/**
 * League.Csv (https://csv.thephpleague.com)
 *
 * (c) Ignace Nyamagana Butera <nyamsprod@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace League\Csv;

use Closure;
use Countable;
use Deprecated;
use Iterator;

/**
 * Represents a Tabular data.
 *
 * @method Iterator fetchColumnByName(string $name) returns a column from its name
 * @method Iterator fetchColumnByOffset(int $offset) returns a column from its offset
 * @method array first() returns the first record from the tabular data.
 * @method array nth(int $nth_record) returns the nth record from the tabular data.
 * @method mixed value(int|string $column = 0) returns a given value from the first element of the tabular data.
 * @method object|null nthAsObject(int $nth, string $className, array $header = []) returns the nth record from the tabular data as an instance of the defined class name.
 * @method object|null firstAsObject(string $className, array $header = []) returns the first record from the tabular data as an instance of the defined class name.
 * @method bool each(Closure $callback) iterates over each record and passes it to a closure. Iteration is interrupted if the closure returns false
 * @method bool exists(Closure $callback) tells whether at least one record satisfies the predicate.
 * @method mixed reduce(Closure $callback, mixed $initial = null) reduces the collection to a single value, passing the result of each iteration into the subsequent iteration
 * @method Iterator map(callable $callback) Run a map over each container members.
 * @method Iterator getObjects(string $className, array $header = []) Returns the tabular data records as an iterator object containing instance of the defined class name.
 * @method Iterator getRecordsAsObject(string $className, array $header = []) Returns the tabular data records as an iterator object containing instance of the defined class name.
 * @method TabularDataReader filter(Query\Predicate|Closure $predicate) returns all the elements of this collection for which your callback function returns `true`
 * @method TabularDataReader slice(int $offset, ?int $length = null) extracts a slice of $length elements starting at position $offset from the Collection.
 * @method TabularDataReader sorted(Query\Sort|Closure $orderBy) sorts the Collection according to the closure provided see Statement::orderBy method
 * @method TabularDataReader select(string|int ...$columnOffsetOrName) extract a selection of the tabular data records columns.
 * @method TabularDataReader selectAllExcept(string|int ...$columnOffsetOrName) specifies the names or index of one or more columns to exclude from the selection of the tabular data records columns.
 * @method TabularDataReader matchingFirstOrFail(string $expression) extract the first found fragment identifier of the tabular data or fail
 * @method TabularDataReader|null matchingFirst(string $expression) extract the first found fragment identifier of the tabular data or return null if none is found
 * @method iterable<int, TabularDataReader> matching(string $expression) extract all found fragment identifiers for the tabular data
 * @method iterable<TabularDataReader> chunkBy(int $recordsCount) Chunk the TabulaDataReader into smaller TabularDataReader instances of the given size or less.
 * @method TabularDataReader mapHeader(array $headers) Returns a new TabulaDataReader with a new set of headers.
 */
interface TabularDataReader extends Countable, TabularData
{
    /**
     * Returns the number of records contained in the tabular data structure
     * excluding the header record.
     */
    public function count(): int;

    /**
     * Returns the tabular data records as an iterator object.
     *
     * Each record is represented as a simple array containing strings or null values.
     *
     * If the tabular data has a header record then each record is combined
     * to the header record and the header record is removed from the iterator.
     *
     * If the tabular data is inconsistent. Missing record fields are
     * filled with null values while extra record fields are strip from
     * the returned object.
     *
     * @param array<string> $header an optional header mapper to use instead of the CSV document header
     *
     * @return Iterator<array-key, array<array-key, mixed>>
     */
    public function getRecords(array $header = []): Iterator;

    /**
     * Returns the next key-value pairs from the tabular data (first
     * column is the key, second column is the value).
     *
     * By default, if no column index is provided:
     * - the first column is used to provide the keys
     * - the second column is used to provide the value
     *
     * @param string|int $offset_index The column index to serve as offset
     * @param string|int $value_index The column index to serve as value
     *
     * @throws UnableToProcessCsv if the column index is invalid or not found
     */
    public function fetchPairs($offset_index = 0, $value_index = 1): Iterator;

    /**
     * DEPRECATION WARNING! This method will be removed in the next major point release.
     *
     * @deprecated since version 9.9.0
     *
     * Returns the nth record from the tabular data.
     *
     * By default, if no index is provided the first record of the tabular data is returned
     *
     * @param int $nth_record the tabular data record offset
     *
     * @throws UnableToProcessCsv if argument is less than 0
     */
    #[Deprecated(message:'use League\Csv\TabularDataReader::nth() instead', since:'league/csv:9.9.0')]
    public function fetchOne(int $nth_record = 0): array;

    /**
     * Returns a single column from the next record of the tabular data.
     *
     * By default, if no value is supplied the first column is fetched
     *
     * @param string|int $index CSV column index
     *
     * @throws UnableToProcessCsv if the column index is invalid or not found
     *
     * @return Iterator<int, mixed>
     */
    public function fetchColumn(string|int $index = 0): Iterator;
}
