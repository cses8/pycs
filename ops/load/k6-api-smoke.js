import http from 'k6/http';
import { check, sleep } from 'k6';

const baseUrl = __ENV.BASE_URL || 'http://localhost:8000';

export const options = {
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<500'],
  },
  scenarios: {
    public_reads: {
      executor: 'ramping-vus',
      stages: [
        { duration: '30s', target: Number(__ENV.TARGET_VUS || 10) },
        { duration: '1m', target: Number(__ENV.TARGET_VUS || 10) },
        { duration: '30s', target: 0 },
      ],
    },
  },
};

export default function () {
  const responses = http.batch([
    ['GET', `${baseUrl}/api/announcements/active`],
    ['GET', `${baseUrl}/api/galleries`],
    ['GET', `${baseUrl}/api/school-calendars`],
    ['GET', `${baseUrl}/api/school-updates`],
  ]);

  for (const response of responses) {
    check(response, {
      'status is 2xx': (res) => res.status >= 200 && res.status < 300,
    });
  }

  sleep(1);
}
