/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import OverviewInpatientEmpty from "./OverviewInpatientEmpty.vue";

describe("OverviewInpatientEmpty", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(OverviewInpatientEmpty);
        expect(wrapper.exists()).toBe(true);
    });
});
