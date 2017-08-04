<?php
use Grimzy\LaravelMysqlSpatial\SpatialServiceProvider;
use Grimzy\LaravelMysqlSpatial\Types\GeometryCollection;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\MultiPoint;
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\TestCase;

class SpatialTest extends TestCase
{
    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';
        $app->register(SpatialServiceProvider::class);

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
        $app['config']->set('database.connections.mysql.database', 'test');
        $app['config']->set('database.connections.mysql.username', 'root');
        $app['config']->set('database.connections.mysql.password', '');

        return $app;
    }

    /**
     * Setup DB before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->onMigrations(function ($migrationClass) {
            (new $migrationClass)->up();
        });
    }

    public function tearDown()
    {
        $this->onMigrations(function ($migrationClass) {
            (new $migrationClass)->down();
        }, true);

        parent::tearDown();
    }

    protected function assertDatabaseHas($table, array $data, $connection = null)
    {
        if (method_exists($this, 'seeInDatabase')) {
            $this->seeInDatabase($table, $data, $connection);
        } else {
            parent::assertDatabaseHas($table, $data, $connection);
        }
    }

    private function onMigrations(\Closure $closure, $reverse_sort = false)
    {
        $fileSystem = new Filesystem();
        $classFinder = new Tools\ClassFinder();

        $migrations = $fileSystem->files(__DIR__ . "/Migrations");
        $reverse_sort ? rsort($migrations, SORT_STRING) : sort($migrations, SORT_STRING);

        foreach ($migrations as $file) {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            $closure($migrationClass);
        }
    }

    public function testSpatialFieldsNotDefinedException() {
        $geo = new NoSpatialFieldsModel();
        $geo->geometry = new Point(1, 2);
        $geo->save();

        $this->setExpectedException(\Grimzy\LaravelMysqlSpatial\Exceptions\SpatialFieldsNotDefinedException::class);
        NoSpatialFieldsModel::all();

    }

    public function testInsertPoint()
    {
        $geo = new GeometryModel();
        $geo->location = new Point(1, 2);
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testInsertLineString()
    {
        $geo = new GeometryModel();

        $geo->location = new Point(1, 2);
        $geo->line = new LineString([new Point(1, 1), new Point(2, 2)]);
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testInsertPolygon()
    {
        $geo = new GeometryModel();

        $geo->location = new Point(1, 2);
        $geo->shape = Polygon::fromWKT("POLYGON((0 10,10 10,10 0,0 0,0 10))");
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testInsertMultiPoint()
    {
        $geo = new GeometryModel();

        $geo->location = new Point(1, 2);
        $geo->multi_locations = new MultiPoint([new Point(1, 1), new Point(2, 2)]);
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testInsertMultiPolygon()
    {
        $geo = new GeometryModel();

        $geo->location = new Point(1, 2);

        $geo->multi_shapes = new MultiPolygon([
            Polygon::fromWKT("POLYGON((0 10,10 10,10 0,0 0,0 10))"),
            Polygon::fromWKT("POLYGON((0 0,0 5,5 5,5 0,0 0))")
        ]);
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testInsertGeometryCollection()
    {
        $geo = new GeometryModel();

        $geo->location = new Point(1, 2);

        $geo->multi_geometries = new GeometryCollection([
            Polygon::fromWKT("POLYGON((0 10,10 10,10 0,0 0,0 10))"),
            Polygon::fromWKT("POLYGON((0 0,0 5,5 5,5 0,0 0))"),
            new Point(0, 0)
        ]);
        $geo->save();
        $this->assertDatabaseHas('geometry', ['id' => $geo->id]);
    }

    public function testUpdate()
    {
        $geo = new GeometryModel();
        $geo->location = new Point(1, 2);
        $geo->save();

        $to_update = GeometryModel::all()->first();
        $to_update->location = new Point(2, 3);
        $to_update->save();

        $this->assertDatabaseHas('geometry', ['id' => $to_update->id]);

        $all = GeometryModel::all();
        $this->assertCount(1, $all);

        $updated = $all->first();
        $this->assertInstanceOf(Point::class, $updated->location);
        $this->assertEquals(2, $updated->location->getLat());
        $this->assertEquals(3, $updated->location->getLng());
    }

    public function testDistance()
    {
        $loc1 = new GeometryModel();
        $loc1->location = new Point(1, 1);
        $loc1->save();

        $loc2 = new GeometryModel();
        $loc2->location = new Point(2, 2); // Distance from loc1: 1.4142135623731
        $loc2->save();

        $loc3 = new GeometryModel();
        $loc3->location = new Point(3, 3); // Distance from loc1: 2.8284271247462
        $loc3->save();

        $a = GeometryModel::distance(2, $loc1->location, 'location')->get();
        $this->assertCount(2, $a);
        $this->assertTrue($a->contains($loc1));
        $this->assertTrue($a->contains($loc2));
        $this->assertFalse($a->contains($loc3));

        // Excluding self
        $b = GeometryModel::distance(2, $loc1->location, 'location', true)->get();
        $this->assertCount(1, $b);
        $this->assertFalse($b->contains($loc1));
        $this->assertTrue($b->contains($loc2));
        $this->assertFalse($b->contains($loc3));

        $c = GeometryModel::distance(1, $loc1->location, 'location')->get();
        $this->assertCount(1, $c);
        $this->assertTrue($c->contains($loc1));
        $this->assertFalse($c->contains($loc2));
        $this->assertFalse($c->contains($loc3));
    }

    public function testDistanceSphere()
    {
        $loc1 = new GeometryModel();
        $loc1->location = new Point(40.767864, -73.971732);
        $loc1->save();

        $loc2 = new GeometryModel();
        $loc2->location = new Point(40.767664, -73.971271); // Distance from loc1: 44.741406484588
        $loc2->save();

        $loc3 = new GeometryModel();
        $loc3->location = new Point(40.761434, -73.977619); // Distance from loc1: 870.06424066202
        $loc3->save();

        $a = GeometryModel::distanceSphere(200, $loc1->location, 'location')->get();
        $this->assertCount(2, $a);
        $this->assertTrue($a->contains($loc1));
        $this->assertTrue($a->contains($loc2));
        $this->assertFalse($a->contains($loc3));

        // Excluding self
        $b = GeometryModel::distanceSphere(200, $loc1->location, 'location', true)->get();
        $this->assertCount(1, $b);
        $this->assertFalse($b->contains($loc1));
        $this->assertTrue($b->contains($loc2));
        $this->assertFalse($b->contains($loc3));

        $c = GeometryModel::distanceSphere(44.741406484587, $loc1->location, 'location')->get();
        $this->assertCount(1, $c);
        $this->assertTrue($c->contains($loc1));
        $this->assertFalse($c->contains($loc2));
        $this->assertFalse($c->contains($loc3));
    }

    public function testDistanceValue()
    {
        $loc1 = new GeometryModel();
        $loc1->location = new Point(1, 1);
        $loc1->save();

        $loc2 = new GeometryModel();
        $loc2->location = new Point(2, 2); // Distance from loc1: 1.4142135623731
        $loc2->save();

        $a = GeometryModel::distanceValue($loc1->location, 'location')->get();
        $this->assertCount(2, $a);
        $this->assertEquals(0, $a[0]->distance);
        $this->assertEquals(1.4142135623, $a[1]->distance); // PHP floats' 11th+ digits don't matter
    }

    public function testDistanceSphereValue() {
        $loc1 = new GeometryModel();
        $loc1->location = new Point(40.767864, -73.971732);
        $loc1->save();

        $loc2 = new GeometryModel();
        $loc2->location = new Point(40.767664, -73.971271); // Distance from loc1: 44.741406484588
        $loc2->save();

        $a = GeometryModel::distanceSphereValue($loc1->location, 'location')->get();
        $this->assertCount(2, $a);
        $this->assertEquals(0, $a[0]->distance);
        $this->assertEquals(44.7414064845, $a[1]->distance); // PHP floats' 11th+ digits don't matter
    }

    public function testBounding() {
        $point = new Point(0, 0);

        $linestring1 = \Grimzy\LaravelMysqlSpatial\Types\LineString::fromWkt("LINESTRING(1 1, 2 2)");
        $linestring2 = \Grimzy\LaravelMysqlSpatial\Types\LineString::fromWkt("LINESTRING(20 20, 24 24)");
        $linestring3 = \Grimzy\LaravelMysqlSpatial\Types\LineString::fromWkt("LINESTRING(0 10, 10 10)");

        $geo1 = new GeometryModel();
        $geo1->location = $point;
        $geo1->line = $linestring1;
        $geo1->save();

        $geo2 = new GeometryModel();
        $geo2->location = $point;
        $geo2->line = $linestring2;
        $geo2->save();

        $geo3 = new GeometryModel();
        $geo3->location = $point;
        $geo3->line = $linestring3;
        $geo3->save();

        $polygon = Polygon::fromWKT("POLYGON((0 10,10 10,10 0,0 0,0 10))");

        $result = GeometryModel::Bounding($polygon, 'line')->get();
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains($geo1));
        $this->assertFalse($result->contains($geo2));
        $this->assertTrue($result->contains($geo3));

    }
}