<?php
namespace Tests\Unit\Production;

use PHPUnit\Framework\TestCase;
use Core\Production\Workflow;

class WorkflowTest extends TestCase {
    private $workflow;
    private $mockDb;

    protected function setUp(): void {
        $this->mockDb = $this->createMock(\PDO::class);
        $this->workflow = new Workflow();
    }

    public function testProcessOrderWithValidFiles() {
        $orderId = 1;
        $mockOrder = [
            'id' => $orderId,
            'files' => [
                ['path' => '/test/file.pdf', 'name' => 'test.pdf']
            ]
        ];

        $result = $this->workflow->processOrder($orderId);
        $this->assertTrue($result);
    }

    public function testQualityCheckFailure() {
        $orderId = 2;
        $result = $this->workflow->processOrder($orderId);
        $this->assertFalse($result);
    }
}
