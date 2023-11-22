/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import OverviewConsultationScheduleEmptyComponent from "./OverviewConsultationScheduleEmpty.vue";

describe("OverviewConsultationScheduleEmptyComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(OverviewConsultationScheduleEmptyComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
