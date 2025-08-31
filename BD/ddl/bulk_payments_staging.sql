CREATE TABLE IF NOT EXISTS bulk_payments_staging 
(
	run_id         VARCHAR(50)  NOT NULL,
	case_id        BIGINT       NOT NULL,
	provider_code  VARCHAR(100) NULL,
	paid_date      DATE         NULL,
	status_before  VARCHAR(50)  NULL,
	status_after   VARCHAR(50)  NULL,
	would_update   TINYINT(1)   NOT NULL,
	reason         VARCHAR(500) NULL,
	executed_at    DATETIME     NOT NULL,
	executed_by    VARCHAR(100) NULL
);